<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Support;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Presets\AbstractChartPreset;

/**
 * Charts that draw a quantity flowing from the values of one dimension to the
 * values of another (graph, chord, flow-lines): 2 dimensions (source,
 * target) and 1 measure. `flows()` is the shared reading of the rows.
 */
abstract class FlowPreset extends AbstractChartPreset
{
    protected function acceptsStructure(DatasetShape $shape): bool
    {
        return $shape->dimensionCount() === 2 && $shape->measures === 1;
    }

    /**
     * The flows of the dataset, one per distinct source and target (repeats are
     * summed), in order of first appearance by source. A flow needs a positive
     * size and two distinct ends: a null, zero or negative value is dropped, and
     * so is a node linked to itself — a cycle sankey and chord cannot lay out and
     * graph would draw as a zero-length line.
     *
     * @return list<array{source: string, target: string, value: int|float}>
     */
    final protected function flows(ChartDataset $dataset): array
    {
        $sources = $dataset->labels($dataset->dimensions[0]->name);
        $targets = $dataset->labels($dataset->dimensions[1]->name);
        $values = $dataset->values($dataset->measures[0]->name);

        $sums = [];
        foreach ($values as $row => $value) {
            if ($value === null || $value <= 0 || $sources[$row] === $targets[$row]) {
                continue;
            }

            $sums[$sources[$row]][$targets[$row]] = ($sums[$sources[$row]][$targets[$row]] ?? 0) + $value;
        }

        $flows = [];
        foreach ($sums as $source => $targetSums) {
            foreach ($targetSums as $target => $value) {
                $flows[] = ['source' => (string) $source, 'target' => (string) $target, 'value' => $value];
            }
        }

        return $flows;
    }
}
