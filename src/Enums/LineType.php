<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Enums;

/**
 * Line dash pattern for a line style.
 */
enum LineType: string
{
    case Solid = 'solid';
    case Dashed = 'dashed';
    case Dotted = 'dotted';
}
