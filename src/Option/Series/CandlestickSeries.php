<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Option\Series;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasData;

final class CandlestickSeries extends Series
{
    use HasData;

    protected function type(): string
    {
        return 'candlestick';
    }

    #[\Override]
    protected function build(): array
    {
        return array_merge(parent::build(), $this->dataArray());
    }
}
