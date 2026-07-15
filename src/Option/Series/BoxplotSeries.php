<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Option\Series;

use Happenv\FilamentEnhancedCharts\Enums\Orient;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasData;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

final class BoxplotSeries extends Series
{
    use HasData;

    private ?string $layout = null;

    /** @var array<int, int|string>|null */
    private ?array $boxWidth = null;

    protected function type(): string
    {
        return 'boxplot';
    }

    /** Box orientation on the cartesian grid: 'horizontal' or 'vertical' (default). */
    public function layout(Orient | string $layout): static
    {
        $this->layout = Normalize::enum($layout);

        return $this;
    }

    /** @param array<int, int|string> $boxWidth [min, max] box width, as pixel numbers or percentage strings of the band width. */
    public function boxWidth(array $boxWidth): static
    {
        $this->boxWidth = $boxWidth;

        return $this;
    }

    #[\Override]
    protected function build(): array
    {
        $series = array_merge(parent::build(), $this->dataArray());

        if ($this->layout !== null) {
            $series['layout'] = $this->layout;
        }
        if ($this->boxWidth !== null) {
            $series['boxWidth'] = $this->boxWidth;
        }

        return $series;
    }
}
