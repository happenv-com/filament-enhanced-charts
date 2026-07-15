<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Option\Series;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasData;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasSymbol;

/**
 * A bar chart drawn with repeated symbols/pictures instead of plain
 * rectangles, on the cartesian coordinate system.
 */
final class PictorialBarSeries extends Series
{
    use HasData;
    use HasSymbol;

    private bool | int | string | null $symbolRepeat = null;

    private ?bool $symbolClip = null;

    private int | float | null $symbolBoundingData = null;

    private ?string $symbolPosition = null;

    /** @var array<int, int|string>|null */
    private ?array $symbolOffset = null;

    private int | string | null $symbolMargin = null;

    private int | string | null $barCategoryGap = null;

    private int | string | null $barGap = null;

    protected function type(): string
    {
        return 'pictorialBar';
    }

    /**
     * Whether/how the symbol repeats along the bar: `true` auto-calculates
     * repeat times and cuts by data, an int fixes the repeat count, and
     * `'fixed'` auto-calculates but does not cut by data.
     */
    public function symbolRepeat(bool | int | string $repeat = true): static
    {
        $this->symbolRepeat = $repeat;

        return $this;
    }

    /** Clips the symbol at the bar's value edge instead of letting it overflow. */
    public function symbolClip(bool $clip = true): static
    {
        $this->symbolClip = $clip;

        return $this;
    }

    /** The value a full symbol represents; defaults to the axis max. */
    public function symbolBoundingData(int | float $value): static
    {
        $this->symbolBoundingData = $value;

        return $this;
    }

    /** Where the symbol sits on the bar: `'start'`, `'end'`, or `'center'`. */
    public function symbolPosition(string $position): static
    {
        $this->symbolPosition = $position;

        return $this;
    }

    /** @param array<int, int|string> $offset A percent/pixel `[x, y]` offset relative to symbolSize. */
    public function symbolOffset(array $offset): static
    {
        $this->symbolOffset = $offset;

        return $this;
    }

    /** Start/end margin around each repeated symbol; a pixel int or a percent string. */
    public function symbolMargin(int | string $margin): static
    {
        $this->symbolMargin = $margin;

        return $this;
    }

    public function barCategoryGap(int | string $barCategoryGap): static
    {
        $this->barCategoryGap = $barCategoryGap;

        return $this;
    }

    public function barGap(int | string $barGap): static
    {
        $this->barGap = $barGap;

        return $this;
    }

    #[\Override]
    protected function build(): array
    {
        $series = array_merge(parent::build(), $this->dataArray(), $this->symbolConfig());

        if ($this->symbolRepeat !== null) {
            $series['symbolRepeat'] = $this->symbolRepeat;
        }
        if ($this->symbolClip !== null) {
            $series['symbolClip'] = $this->symbolClip;
        }
        if ($this->symbolBoundingData !== null) {
            $series['symbolBoundingData'] = $this->symbolBoundingData;
        }
        if ($this->symbolPosition !== null) {
            $series['symbolPosition'] = $this->symbolPosition;
        }
        if ($this->symbolOffset !== null) {
            $series['symbolOffset'] = $this->symbolOffset;
        }
        if ($this->symbolMargin !== null) {
            $series['symbolMargin'] = $this->symbolMargin;
        }
        if ($this->barCategoryGap !== null) {
            $series['barCategoryGap'] = $this->barCategoryGap;
        }
        if ($this->barGap !== null) {
            $series['barGap'] = $this->barGap;
        }

        return $series;
    }
}
