<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Option\Axis;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasDataExtent;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasNumberFormat;

final class ValueAxis extends Axis
{
    use HasDataExtent;
    use HasNumberFormat;

    protected function type(): string
    {
        return 'value';
    }

    #[\Override]
    protected function build(): array
    {
        return $this->withNumberFormat(array_merge(parent::build(), $this->dataExtentArray()));
    }
}
