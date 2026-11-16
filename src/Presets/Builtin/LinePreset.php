<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Option\Series\LineSeries;
use Happenv\FilamentEnhancedCharts\Presets\Support\CartesianPreset;

final class LinePreset extends CartesianPreset
{
    public function name(): string
    {
        return 'line';
    }

    public function description(): string
    {
        return 'Shows how one or more measures change along an ordered dimension such as time.';
    }

    public function requirement(): string
    {
        return self::ONE_DIMENSION_MEASURES;
    }

    protected function acceptsStructure(DatasetShape $shape): bool
    {
        return $this->oneDimensionMeasures($shape);
    }

    protected function series(string $name, array $values, int $axis = 0): LineSeries
    {
        return LineSeries::make()->name($name)->data($values);
    }
}
