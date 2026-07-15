<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Enums;

/**
 * ECharts' built-in marker symbols. For a custom marker, pass an `image://…`
 * or `path://…` string to `symbol()` instead of a case.
 */
enum Symbol: string
{
    case Circle = 'circle';
    case EmptyCircle = 'emptyCircle';
    case Rect = 'rect';
    case RoundRect = 'roundRect';
    case Triangle = 'triangle';
    case Diamond = 'diamond';
    case Pin = 'pin';
    case Arrow = 'arrow';
    case None = 'none';
}
