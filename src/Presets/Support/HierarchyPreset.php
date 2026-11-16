<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Support;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\Series;
use Happenv\FilamentEnhancedCharts\Presets\AbstractChartPreset;

/**
 * Charts that draw a hierarchy (treemap, sunburst): one dimension makes a flat
 * set of nodes, two dimensions nest the second inside the first; the measure is
 * the size of each leaf.
 */
abstract class HierarchyPreset extends AbstractChartPreset
{
    /** @param  list<array<string, mixed>>  $nodes */
    abstract protected function series(array $nodes): Series;

    final public function requirement(): string
    {
        return '1 or 2 dimensions (category or time; the second is nested inside the first) and 1 measure';
    }

    final public function accepts(DatasetShape $shape): bool
    {
        return in_array($shape->dimensionCount(), [1, 2], true) && $shape->measures === 1;
    }

    final protected function build(ChartDataset $dataset): Option
    {
        return Option::make()
            ->tooltip($this->itemTooltip())
            ->series($this->series($this->nodes($dataset)));
    }

    /** @return list<array<string, mixed>> */
    private function nodes(ChartDataset $dataset): array
    {
        $measure = $dataset->measures[0]->name;
        $parent = $dataset->dimensions[0]->name;

        if (count($dataset->dimensions) === 1) {
            return array_values(array_map(
                static fn (array $total): array => ['name' => $total['name'], 'value' => $total['value']],
                array_filter($dataset->totals($parent, $measure), static fn (array $total): bool => $total['value'] !== null),
            ));
        }

        $child = $dataset->dimensions[1]->name;
        $parents = $dataset->labels($parent);
        $children = $dataset->labels($child);
        $values = $dataset->values($measure);

        $tree = [];
        foreach ($parents as $index => $name) {
            $value = $values[$index];
            if ($value === null) {
                continue;
            }

            $tree[$name][$children[$index]] = ($tree[$name][$children[$index]] ?? 0) + $value;
        }

        $nodes = [];
        foreach ($tree as $name => $leaves) {
            $branch = [];
            foreach ($leaves as $leaf => $value) {
                $branch[] = ['name' => (string) $leaf, 'value' => $value];
            }

            $nodes[] = ['name' => (string) $name, 'children' => $branch];
        }

        return $nodes;
    }
}
