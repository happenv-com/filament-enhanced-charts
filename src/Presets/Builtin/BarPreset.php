<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Option\Series\BarSeries;
use Happenv\FilamentEnhancedCharts\Presets\Support\CartesianPreset;

final class BarPreset extends CartesianPreset
{
    public function name(): string
    {
        return 'bar';
    }

    public function description(): string
    {
        return 'Compares one or more measures across the values of a single dimension with vertical bars.';
    }

    public function requirement(): string
    {
        return self::ONE_DIMENSION_MEASURES;
    }

    public function accepts(DatasetShape $shape): bool
    {
        return $this->oneDimensionMeasures($shape);
    }

    protected function series(string $name, array $values): BarSeries
    {
        return BarSeries::make()->name($name)->data($values);
    }
}
