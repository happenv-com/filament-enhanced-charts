<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Option\Series;

use Happenv\FilamentEnhancedCharts\Enums\Sort;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasData;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLayout;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasSize;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

final class FunnelSeries extends Series
{
    use HasData;
    use HasLayout;
    use HasSize;

    private ?string $sort = null;

    private ?int $gap = null;

    private ?string $funnelAlign = null;

    private int | float | null $min = null;

    private int | float | null $max = null;

    private int | string | null $minSize = null;

    private int | string | null $maxSize = null;

    protected function type(): string
    {
        return 'funnel';
    }

    public function sort(Sort | string $sort): static
    {
        $this->sort = Normalize::enum($sort);

        return $this;
    }

    public function gap(int $gap): static
    {
        $this->gap = $gap;

        return $this;
    }

    /** Horizontal alignment of the funnel: 'left', 'center' (default), or 'right'. */
    public function funnelAlign(string $funnelAlign): static
    {
        $this->funnelAlign = $funnelAlign;

        return $this;
    }

    /** The minimum data value mapped to `minSize()` — pieces below it still render at `minSize()`. */
    public function min(int | float $min): static
    {
        $this->min = $min;

        return $this;
    }

    /** The maximum data value mapped to `maxSize()` — pieces above it still render at `maxSize()`. */
    public function max(int | float $max): static
    {
        $this->max = $max;

        return $this;
    }

    /** The width/height (per `orient()`) of the smallest piece — a pixel number or percentage string. */
    public function minSize(int | string $minSize): static
    {
        $this->minSize = $minSize;

        return $this;
    }

    /** The width/height (per `orient()`) of the largest piece — a pixel number or percentage string. */
    public function maxSize(int | string $maxSize): static
    {
        $this->maxSize = $maxSize;

        return $this;
    }

    #[\Override]
    protected function build(): array
    {
        $series = array_merge(parent::build(), $this->dataArray());
        if ($this->sort !== null) {
            $series['sort'] = $this->sort;
        }
        if ($this->gap !== null) {
            $series['gap'] = $this->gap;
        }
        $series = array_merge($series, $this->sizeArray());
        if ($this->funnelAlign !== null) {
            $series['funnelAlign'] = $this->funnelAlign;
        }
        if ($this->min !== null) {
            $series['min'] = $this->min;
        }
        if ($this->max !== null) {
            $series['max'] = $this->max;
        }
        if ($this->minSize !== null) {
            $series['minSize'] = $this->minSize;
        }
        if ($this->maxSize !== null) {
            $series['maxSize'] = $this->maxSize;
        }

        return array_merge($series, $this->boxLayoutArray());
    }
}
