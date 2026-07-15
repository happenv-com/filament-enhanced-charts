<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Enums;

/**
 * How `dataZoom` filters data points outside the zoomed window: excludes them
 * everywhere (`Filter`), excludes them only from axes that would otherwise be
 * rescaled (`WeakFilter`), keeps them but sets their value to `NaN`
 * (`Empty`), or does not filter at all — only zooms the axis (`None`).
 */
enum DataZoomFilterMode: string
{
    case Filter = 'filter';
    case WeakFilter = 'weakFilter';
    case Empty = 'empty';
    case None = 'none';
}
