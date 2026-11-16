<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Option\Series\LineSeries;
use Happenv\FilamentEnhancedCharts\Presets\Support\CartesianPreset;

final class MultiLinePreset extends CartesianPreset
{
    public function name(): string
    {
        return 'multi-line';
    }

    public function description(): string
    {
        return 'Compares how a measure changes along an ordered dimension for each value of a second dimension, one line each.';
    }

    public function requirement(): string
    {
        return self::BREAKDOWN;
    }

    protected function acceptsStructure(DatasetShape $shape): bool
    {
        return $this->acceptsBreakdown($shape);
    }

    protected function series(string $name, array $values, int $axis = 0): LineSeries
    {
        return LineSeries::make()->name($name)->data($values);
    }
}
