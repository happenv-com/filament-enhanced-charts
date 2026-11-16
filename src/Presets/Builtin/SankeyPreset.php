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
    public function name(): string
    {
        return 'sankey';
    }

    public function description(): string
    {
        return 'Shows how a quantity flows from the values of one dimension (source) to the values of another (target), with link width proportional to the flow.';
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
            // A flow needs a positive size and two distinct ends: a node linked to itself is a cycle ECharts cannot lay out.
            if ($value === null || $value <= 0 || $sources[$row] === $targets[$row]) {
                continue;
            }

            $flows[$sources[$row]][$targets[$row]] = ($flows[$sources[$row]][$targets[$row]] ?? 0) + $value;
        }

        $links = [];
        foreach ($flows as $source => $targetFlows) {
            foreach ($targetFlows as $target => $value) {
                $links[] = SankeyLink::make((string) $source, (string) $target, $value);
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
}
