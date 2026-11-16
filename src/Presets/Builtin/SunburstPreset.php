<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Option\Series\SunburstSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\Emphasis;
use Happenv\FilamentEnhancedCharts\Presets\Support\HierarchyPreset;

final class SunburstPreset extends HierarchyPreset
{
    public function name(): string
    {
        return 'sunburst';
    }

    public function description(): string
    {
        return 'Shows how a whole divides into parts and sub-parts as concentric rings, one ring per dimension.';
    }

    protected function series(array $nodes): SunburstSeries
    {
        return SunburstSeries::make()
            ->data($nodes)
            ->radius(['12%', '90%'])
            ->center(['50%', '50%'])
            ->emphasis(Emphasis::make()->focus('ancestor'));
    }
}
