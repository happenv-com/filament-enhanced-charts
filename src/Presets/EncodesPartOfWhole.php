<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets;

use Happenv\FilamentEnhancedCharts\Data\DatasetShape;

/**
 * Optional companion of `ChartPreset` for a preset that encodes part-of-whole:
 * it draws shares, stacks to a total, or sizes a node by the sum of its flows.
 * Such a chart is only true when the values of the dimension it partitions by
 * add up to the whole, which a multi-valued dimension (see
 * `DatasetDimension::multiValued()`) does not — an entity counted once per value
 * is counted several times in the sum.
 *
 * The registry and `AbstractChartPreset` refuse a preset implementing this
 * whenever any dimension it names is multi-valued. It is a separate interface so
 * that `ChartPreset` stays as it was: a third-party preset implementing only
 * `ChartPreset` keeps compiling and is treated as not encoding part-of-whole.
 * `AbstractChartPreset` implements it with no partitioning dimension, so a preset
 * extending it opts in by overriding `partitioningDimensions()`.
 */
interface EncodesPartOfWhole
{
    /**
     * The positions of the dimensions of a dataset of this shape that the chart
     * partitions a total by — the ones whose values it adds up or stacks. Empty
     * when the chart encodes no part-of-whole for this shape.
     *
     * @return list<int>
     */
    public function partitioningDimensions(DatasetShape $shape): array;
}
