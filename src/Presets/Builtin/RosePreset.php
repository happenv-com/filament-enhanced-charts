<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Enums\RoseType;
use Happenv\FilamentEnhancedCharts\Option\Series\PieSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\Emphasis;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;
use Happenv\FilamentEnhancedCharts\Presets\Support\NamedSlicesPreset;

final class RosePreset extends NamedSlicesPreset
{
    public function name(): string
    {
        return 'rose';
    }

    public function description(): string
    {
        return 'Shows parts of a whole as a nightingale rose, where the radius of each petal grows with its value so differences stand out.';
    }

    protected function series(array $slices): PieSeries
    {
        return PieSeries::make()->radius(['15%', '66%'])->center(['50%', '45%'])->roseType(RoseType::Radius)->itemStyle(ItemStyle::make()->borderRadius(4))->data($slices)->label(Label::make()->show(false))->labelLine(false)->emphasis(Emphasis::make()->label(Label::make()->show()->bold()));
    }
}
