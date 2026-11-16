<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Enums\Symbol;
use Happenv\FilamentEnhancedCharts\Option\Series\PictorialBarSeries;
use Happenv\FilamentEnhancedCharts\Presets\Support\CartesianPreset;

final class PictorialBarPreset extends CartesianPreset
{
    public function name(): string
    {
        return 'pictorial-bar';
    }

    public function description(): string
    {
        return 'Compares a measure across the values of a dimension like a bar chart, with every bar built from a stack of small rounded pills so the amounts can be counted at a glance.';
    }

    public function requirement(): string
    {
        return self::ONE_DIMENSION_ONE_MEASURE;
    }

    public function accepts(DatasetShape $shape): bool
    {
        return $this->oneDimensionMeasures($shape, 1, 1);
    }

    protected function series(string $name, array $values, int $axis = 0): PictorialBarSeries
    {
        return PictorialBarSeries::make()
            ->name($name)
            ->symbol(Symbol::RoundRect)
            ->symbolRepeat(true)
            ->symbolClip(true)
            ->symbolSize(['70%', 10])
            ->symbolMargin('18%')
            ->data($values);
    }
}
