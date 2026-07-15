<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Option\Axis;

final class TimeAxis extends Axis
{
    protected function type(): string
    {
        return 'time';
    }
}
