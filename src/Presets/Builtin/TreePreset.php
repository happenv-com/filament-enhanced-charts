<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetDimension;
use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Enums\TreeLayout;
use Happenv\FilamentEnhancedCharts\Enums\TreeOrient;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\TreeSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\Emphasis;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;
use Happenv\FilamentEnhancedCharts\Presets\AbstractChartPreset;

final class TreePreset extends AbstractChartPreset
{
    public function name(): string
    {
        return 'tree';
    }

    public function description(): string
    {
        return 'Shows how a whole branches into parts and sub-parts as a tree of connected nodes under a single root, each node carrying the total of everything below it.';
    }

    public function requirement(): string
    {
        return '1 to 3 dimensions (category, time or region; each nested inside the previous one, the first nearest the root) and 1 measure (the value of a leaf; a parent is the sum of its leaves)' . self::SINGLE_VALUED_DIMENSIONS;
    }

    /** A parent node is the sum of its leaves, so every level partitions a total. */
    public function partitioningDimensions(DatasetShape $shape): array
    {
        return array_keys($shape->dimensions);
    }

    protected function acceptsStructure(DatasetShape $shape): bool
    {
        return $shape->dimensionCount() >= 1 && $shape->dimensionCount() <= 3 && $shape->measures === 1;
    }

    protected function build(ChartDataset $dataset): Option
    {
        $levels = array_map(
            static fn (DatasetDimension $dimension): array => $dataset->labels($dimension->name),
            $dataset->dimensions,
        );

        $leaves = [];
        foreach ($dataset->values($dataset->measures[0]->name) as $row => $value) {
            if ($value !== null) {
                $leaves[] = ['path' => array_column($levels, $row), 'value' => $value];
            }
        }

        return Option::make()
            ->tooltip($this->itemTooltip())
            ->series(
                TreeSeries::make()
                    ->data([$this->node($dataset->measures[0]->label, $leaves)])
                    ->layout(TreeLayout::Orthogonal)
                    ->orient(TreeOrient::LeftRight)
                    ->symbolSize(8)
                    ->left('8%')
                    ->right('24%')
                    ->top('6%')
                    ->bottom('6%')
                    ->expandAndCollapse()
                    ->initialTreeDepth(-1)
                    ->label(Label::make()->position('left')->verticalAlign('middle')->align('right'))
                    ->leaves(['label' => ['position' => 'right', 'verticalAlign' => 'middle', 'align' => 'left']])
                    ->emphasis(Emphasis::make()->focus('descendant')),
            );
    }

    /**
     * A node over the leaves below it: its value is their sum, its children
     * group them by the next label on their path.
     *
     * @param  list<array{path: list<string>, value: int|float}>  $leaves
     * @return array<string, mixed>
     */
    private function node(string $name, array $leaves): array
    {
        $item = ['name' => $name, 'value' => array_sum(array_column($leaves, 'value'))];

        $groups = [];
        foreach ($leaves as $leaf) {
            $head = array_shift($leaf['path']);

            if ($head !== null) {
                $groups[$head][] = $leaf;
            }
        }

        $children = [];
        foreach ($groups as $childName => $below) {
            $children[] = $this->node((string) $childName, $below);
        }

        return $children === [] ? $item : $item + ['children' => $children];
    }
}
