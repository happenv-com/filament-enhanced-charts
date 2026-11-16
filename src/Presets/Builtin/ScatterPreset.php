<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Option\Series\ScatterSeries;
use Happenv\FilamentEnhancedCharts\Presets\Support\PointsPreset;

final class ScatterPreset extends PointsPreset
{
    public function name(): string
    {
        return 'scatter';
    }

    public function description(): string
    {
        return 'Plots one measure against another so a relationship or outlier shows up, with every dimension value as a labelled point.';
    }

    protected function point(string $name, array $point): ScatterSeries
    {
        return ScatterSeries::make()->name($name)->symbolSize(12)->data([$point]);
    }
}
