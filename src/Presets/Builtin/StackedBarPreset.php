<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Option\Series\BarSeries;
use Happenv\FilamentEnhancedCharts\Presets\Support\CartesianPreset;

final class StackedBarPreset extends CartesianPreset
{
    public function name(): string
    {
        return 'stacked-bar';
    }

    public function description(): string
    {
        return 'Shows how the total of a measure per dimension value is composed of the values of a second dimension.';
    }

    public function requirement(): string
    {
        return self::BREAKDOWN . '; with 2 dimensions the second one (the stacked series) must be single-valued';
    }

    /** The stack on top of each x is the total of the second dimension (the series), so that one must add up. */
    public function partitioningDimensions(DatasetShape $shape): array
    {
        return $shape->dimensionCount() === 2 ? [1] : [];
    }

    /** With one dimension the measures themselves are stacked, so their total must make sense. */
    public function stacksMeasures(DatasetShape $shape): bool
    {
        return $shape->dimensionCount() === 1;
    }

    protected function acceptsStructure(DatasetShape $shape): bool
    {
        return $this->acceptsBreakdown($shape);
    }

    protected function series(string $name, array $values, int $axis = 0): BarSeries
    {
        return BarSeries::make()->name($name)->stack($axis === 0 ? 'total' : 'total-' . $axis)->data($values);
    }
}
