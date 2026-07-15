<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Enums;

/**
 * Layout algorithm for a graph series: how nodes are positioned.
 */
enum GraphLayout: string
{
    case None = 'none';
    case Circular = 'circular';
    case Force = 'force';
}
