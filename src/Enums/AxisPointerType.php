<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Enums;

/**
 * How an axis pointer highlights the hovered value: a `line`, a `shadow`
 * band, a `cross` spanning both axes of a grid, or `none`.
 */
enum AxisPointerType: string
{
    case Line = 'line';
    case Shadow = 'shadow';
    case Cross = 'cross';
    case None = 'none';
}
