<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Option\Series;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasData;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasSymbol;

final class ScatterSeries extends Series
{
    use HasData;
    use HasSymbol;

    protected function type(): string
    {
        return 'scatter';
    }

    #[\Override]
    protected function build(): array
    {
        return array_merge(parent::build(), $this->dataArray(), $this->symbolConfig());
    }
}
