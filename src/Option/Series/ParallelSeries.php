<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Option\Series;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasData;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLineStyle;

final class ParallelSeries extends Series
{
    use HasData;
    use HasLineStyle;

    private int | float | null $inactiveOpacity = null;

    private int | float | null $activeOpacity = null;

    private ?bool $smooth = null;

    protected function type(): string
    {
        return 'parallel';
    }

    /** Opacity of a line that is NOT hit by the current brush selection. */
    public function inactiveOpacity(int | float $opacity): static
    {
        $this->inactiveOpacity = $opacity;

        return $this;
    }

    /** Opacity of a line that IS hit by the current brush selection. */
    public function activeOpacity(int | float $opacity): static
    {
        $this->activeOpacity = $opacity;

        return $this;
    }

    public function smooth(bool $smooth = true): static
    {
        $this->smooth = $smooth;

        return $this;
    }

    #[\Override]
    protected function build(): array
    {
        $series = array_merge(parent::build(), $this->dataArray(), $this->lineStyleArray());

        if ($this->inactiveOpacity !== null) {
            $series['inactiveOpacity'] = $this->inactiveOpacity;
        }
        if ($this->activeOpacity !== null) {
            $series['activeOpacity'] = $this->activeOpacity;
        }
        if ($this->smooth !== null) {
            $series['smooth'] = $this->smooth;
        }

        return $series;
    }
}
