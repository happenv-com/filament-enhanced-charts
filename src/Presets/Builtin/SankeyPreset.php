<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\SankeyLink;
use Happenv\FilamentEnhancedCharts\Option\Series\SankeySeries;
use Happenv\FilamentEnhancedCharts\Option\Style\Emphasis;
use Happenv\FilamentEnhancedCharts\Presets\AbstractChartPreset;

final class SankeyPreset extends AbstractChartPreset
{
    /**
     * Appended (invisibly) to a target-stage node name. The two dimensions are
     * two stages, so "A" before and "A" after are different nodes; ECharts
     * identifies a node by its name alone, and one shared node would turn
     * A→B plus B→A into a cycle it cannot lay out. A marker already in a
     * label is doubled first, so a source name always carries an even number
     * of them and a target name an odd one — the two can never collide.
     */
    private const string TARGET_STAGE_MARK = "\u{200B}";

    public function name(): string
    {
        return 'sankey';
    }

    public function description(): string
    {
        return 'Shows how a quantity flows from the values of one dimension (source stage) to the values of another (target stage), with link width proportional to the flow.';
    }

    public function requirement(): string
    {
        return '2 dimensions (category or time; the first is the source, the second the target) and 1 measure';
    }

    public function accepts(DatasetShape $shape): bool
    {
        return $shape->dimensionCount() === 2 && $shape->measures === 1;
    }

    protected function build(ChartDataset $dataset): Option
    {
        $sources = $dataset->labels($dataset->dimensions[0]->name);
        $targets = $dataset->labels($dataset->dimensions[1]->name);
        $values = $dataset->values($dataset->measures[0]->name);

        $flows = [];
        foreach ($values as $row => $value) {
            if ($value === null || $value <= 0) {
                continue;
            }

            $flows[$sources[$row]][$targets[$row]] = ($flows[$sources[$row]][$targets[$row]] ?? 0) + $value;
        }

        $links = [];
        foreach ($flows as $source => $targetFlows) {
            foreach ($targetFlows as $target => $value) {
                $target = (string) $target;
                $links[] = SankeyLink::make($this->stageName((string) $source), $this->stageName($target) . self::TARGET_STAGE_MARK, $value);
            }
        }

        return Option::make()
            ->tooltip($this->itemTooltip())
            ->series(
                SankeySeries::make()
                    ->links($links)
                    ->left(8)
                    ->right(96)
                    ->top(16)
                    ->bottom(16)
                    ->emphasis(Emphasis::make()->focus('adjacency')),
            );
    }

    private function stageName(string $label): string
    {
        return str_replace(self::TARGET_STAGE_MARK, self::TARGET_STAGE_MARK . self::TARGET_STAGE_MARK, $label);
    }
}
