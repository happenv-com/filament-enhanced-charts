<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Enums;

/**
 * Layout algorithm for a tree series: orthogonal (classic node-link tree) or
 * radial (nodes placed on concentric rings around the root).
 */
enum TreeLayout: string
{
    case Orthogonal = 'orthogonal';
    case Radial = 'radial';
}
