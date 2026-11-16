<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Option\Series\TreemapSeries;
use Happenv\FilamentEnhancedCharts\Presets\Support\HierarchyPreset;

final class TreemapPreset extends HierarchyPreset
{
    public function name(): string
    {
        return 'treemap';
    }

    public function description(): string
    {
        return 'Shows the relative size of many parts as nested rectangles, optionally grouped by a second dimension.';
    }

    protected function series(array $nodes): TreemapSeries
    {
        return TreemapSeries::make()
            ->data($nodes)
            ->left(8)
            ->right(8)
            ->top(8)
            ->bottom(32)
            ->upperLabel(true);
    }
}
