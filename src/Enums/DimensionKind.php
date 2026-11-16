<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Enums;

/**
 * What a `ChartDataset` dimension holds: unordered labels (`Category`) or an
 * ordered sequence of already-formatted time buckets (`Time`). Presets use the
 * distinction to decide what they accept (a calendar needs a time dimension) —
 * a time dimension is still just a string label, never parsed as a date by the
 * cartesian presets.
 */
enum DimensionKind: string
{
    case Category = 'category';
    case Time = 'time';
}
