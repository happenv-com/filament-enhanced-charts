<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Enums;

/**
 * Sort order for a funnel series' data.
 */
enum Sort: string
{
    case Ascending = 'ascending';
    case Descending = 'descending';
    case None = 'none';
}
