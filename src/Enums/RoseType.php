<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Enums;

/**
 * Nightingale/rose mode for a pie series: vary slice `radius` by value, or
 * keep the radius fixed and vary the `area` instead.
 */
enum RoseType: string
{
    case Radius = 'radius';
    case Area = 'area';
}
