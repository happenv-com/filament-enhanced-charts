<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Option\Series;

use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasData;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

final class CustomSeries extends Series
{
    use HasData;

    /** @var array{__js__: string}|null */
    private ?array $renderItem = null;

    protected function type(): string
    {
        return 'custom';
    }

    public function renderItem(RawJs | string $renderItem): static
    {
        $this->renderItem = Normalize::js($renderItem);

        return $this;
    }

    #[\Override]
    protected function build(): array
    {
        $series = array_merge(parent::build(), $this->dataArray());
        if ($this->renderItem !== null) {
            $series['renderItem'] = $this->renderItem;
        }

        return $series;
    }
}
