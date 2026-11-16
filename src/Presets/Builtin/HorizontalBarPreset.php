<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Option\Series\BarSeries;
use Happenv\FilamentEnhancedCharts\Presets\Support\CartesianPreset;

final class HorizontalBarPreset extends CartesianPreset
{
    public function name(): string
    {
        return 'horizontal-bar';
    }

    public function description(): string
    {
        return 'Compares measures across the values of a dimension with horizontal bars, which suits long labels and rankings.';
    }

    public function requirement(): string
    {
        return self::ONE_DIMENSION_MEASURES;
    }

    public function accepts(DatasetShape $shape): bool
    {
        return $this->oneDimensionMeasures($shape);
    }

    protected function horizontal(): bool
    {
        return true;
    }

    protected function series(string $name, array $values, int $axis = 0): BarSeries
    {
        return BarSeries::make()->name($name)->data($values);
    }
}
