<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Option\Series\EffectScatterSeries;
use Happenv\FilamentEnhancedCharts\Presets\Support\PointsPreset;

final class EffectScatterPreset extends PointsPreset
{
    public function name(): string
    {
        return 'effect-scatter';
    }

    public function description(): string
    {
        return 'Plots one measure against another like a scatter chart, with every point rippling so the few points that matter draw the eye.';
    }

    protected function point(string $name, array $point): EffectScatterSeries
    {
        return EffectScatterSeries::make()
            ->name($name)
            ->symbolSize(12)
            ->effectType('ripple')
            ->rippleEffect(['scale' => 3, 'period' => 4])
            ->data([$point]);
    }
}
