<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Option\Series\PieSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\Emphasis;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;
use Happenv\FilamentEnhancedCharts\Presets\Support\NamedSlicesPreset;

final class DonutPreset extends NamedSlicesPreset
{
    public function name(): string
    {
        return 'donut';
    }

    public function description(): string
    {
        return 'Shows how a whole splits into parts as a ring, leaving room to read the highlighted slice in the centre.';
    }

    protected function series(array $slices): PieSeries
    {
        return PieSeries::make()->radius(['42%', '66%'])->center(['50%', '45%'])->padAngle(2)->itemStyle(ItemStyle::make()->borderRadius(6))->data($slices)->label(Label::make()->show(false))->labelLine(false)->emphasis(Emphasis::make()->label(Label::make()->show()->position('center')->bold()->fontSize(18)));
    }
}
