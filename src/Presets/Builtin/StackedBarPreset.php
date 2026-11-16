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
        return self::BREAKDOWN;
    }

    public function accepts(DatasetShape $shape): bool
    {
        return $this->acceptsBreakdown($shape);
    }

    protected function series(string $name, array $values, int $axis = 0): BarSeries
    {
        return BarSeries::make()->name($name)->stack($axis === 0 ? 'total' : 'total-' . $axis)->data($values);
    }
}
