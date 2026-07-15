<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Enums;

/**
 * Horizontal alignment of nodes in a sankey series.
 */
enum NodeAlign: string
{
    case Justify = 'justify';
    case Left = 'left';
    case Right = 'right';
}
