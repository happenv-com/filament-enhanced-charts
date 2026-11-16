<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets;

use Happenv\FilamentEnhancedCharts\Data\DatasetShape;

/**
 * Optional companion of `ChartPreset` for a preset that stacks several
 * *measures* on top of each other (one dimension and two or more measures in
 * `stacked-bar`): the stack reads as their total, which is meaningless when a
 * measure is non-additive (a ratio, an average). It is separate from
 * `EncodesPartOfWhole`, which is about the dimensions a total is split by.
 *
 * The registry and `AbstractChartPreset` refuse a preset implementing this
 * whenever it stacks measures of a shape that has a non-additive one.
 * `AbstractChartPreset` implements it as "never", so a preset extending it opts
 * in by overriding `stacksMeasures()`.
 */
interface StacksMeasures
{
    /** Whether the chart stacks the measures of a dataset of this shape into a total. */
    public function stacksMeasures(DatasetShape $shape): bool;
}
