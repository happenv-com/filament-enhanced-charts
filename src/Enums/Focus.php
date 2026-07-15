<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Enums;

/**
 * What else dims/highlights alongside a hovered element: nothing (`none`),
 * just itself (`self`), its whole series, or its graph `adjacency`.
 */
enum Focus: string
{
    case None = 'none';
    case Self = 'self';
    case Series = 'series';
    case Adjacency = 'adjacency';
    case Ancestor = 'ancestor';
    case Descendant = 'descendant';
}
