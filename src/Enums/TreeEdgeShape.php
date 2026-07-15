<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Enums;

/**
 * Shape of the edge connecting a tree node to its parent.
 */
enum TreeEdgeShape: string
{
    case Curve = 'curve';
    case Polyline = 'polyline';
}
