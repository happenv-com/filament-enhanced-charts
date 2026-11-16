<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Option\Series\PieSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\Emphasis;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;
use Happenv\FilamentEnhancedCharts\Presets\Support\NamedSlicesPreset;

final class PiePreset extends NamedSlicesPreset
{
    public function name(): string
    {
        return 'pie';
    }

    public function description(): string
    {
        return 'Shows how a whole splits into parts, each dimension value as a slice of the circle.';
    }

    protected function series(array $slices): PieSeries
    {
        return PieSeries::make()->radius('62%')->center(['50%', '45%'])->data($slices)->label(Label::make()->show(false))->labelLine(false)->emphasis(Emphasis::make()->label(Label::make()->show()->bold()));
    }
}
