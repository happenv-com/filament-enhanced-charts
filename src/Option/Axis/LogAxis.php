<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Option\Axis;

final class LogAxis extends Axis
{
    protected function type(): string
    {
        return 'log';
    }
}
