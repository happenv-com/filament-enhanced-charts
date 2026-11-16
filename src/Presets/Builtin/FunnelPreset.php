<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Enums\Sort;
use Happenv\FilamentEnhancedCharts\Option\Series\FunnelSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;
use Happenv\FilamentEnhancedCharts\Presets\Support\NamedSlicesPreset;

final class FunnelPreset extends NamedSlicesPreset
{
    public function name(): string
    {
        return 'funnel';
    }

    public function description(): string
    {
        return 'Shows how a value narrows stage by stage, with each dimension value as a funnel step sorted from largest to smallest.';
    }

    protected function series(array $slices): FunnelSeries
    {
        return FunnelSeries::make()->sort(Sort::Descending)->gap(2)->left('8%')->right('8%')->top(8)->bottom(36)->label(Label::make()->show()->position('inside'))->data($slices);
    }
}
