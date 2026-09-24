<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Option\Axis;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasDataExtent;

final class TimeAxis extends Axis
{
    use HasDataExtent;

    protected function type(): string
    {
        return 'time';
    }

    #[\Override]
    protected function build(): array
    {
        return array_merge(parent::build(), $this->dataExtentArray());
    }
}
