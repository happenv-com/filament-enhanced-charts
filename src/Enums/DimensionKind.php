<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Enums;

/**
 * What a `ChartDataset` dimension holds: unordered labels (`Category`), an
 * ordered sequence of already-formatted time buckets (`Time`) or ISO 3166-1
 * alpha-2 country codes in uppercase (`Region`, e.g. `PL`). Presets use the
 * distinction to decide what they accept (a calendar needs a time dimension, a
 * world map a region one) — a time dimension is still just a string label,
 * never parsed as a date by the cartesian presets, and a region is a label
 * everywhere except the geo presets, so every preset that takes a category
 * takes a region too.
 */
enum DimensionKind: string
{
    case Category = 'category';
    case Time = 'time';
    case Region = 'region';
}
