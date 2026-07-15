<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Enums;

enum AxisType: string
{
    case Value = 'value';
    case Category = 'category';
    case Time = 'time';
    case Log = 'log';
}
