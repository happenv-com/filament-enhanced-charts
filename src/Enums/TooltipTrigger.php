<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Enums;

/**
 * What triggers the tooltip: a data `item`, the whole `axis`, or `none`.
 */
enum TooltipTrigger: string
{
    case Item = 'item';
    case Axis = 'axis';
    case None = 'none';
}
