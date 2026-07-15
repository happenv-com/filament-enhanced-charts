<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Option\Series;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasData;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasStack;

final class BarSeries extends Series
{
    use HasData;
    use HasStack;

    private int | string | null $barWidth = null;

    private int | string | null $barGap = null;

    private int | string | null $barCategoryGap = null;

    private ?bool $showBackground = null;

    /** @var array<string, mixed>|null */
    private ?array $backgroundStyle = null;

    private ?bool $roundCap = null;

    protected function type(): string
    {
        return 'bar';
    }

    public function barWidth(int | string $width): static
    {
        $this->barWidth = $width;

        return $this;
    }

    /** The gap between bars of different series sharing a category, relative to bar width (e.g. `'30%'`) or in px. */
    public function barGap(int | string $barGap): static
    {
        $this->barGap = $barGap;

        return $this;
    }

    /** The gap between bar categories, relative to bar width (e.g. `'20%'`) or in px. */
    public function barCategoryGap(int | string $barCategoryGap): static
    {
        $this->barCategoryGap = $barCategoryGap;

        return $this;
    }

    /** Draws a full-length background bar behind each bar, styled via `backgroundStyle()`. */
    public function showBackground(bool $showBackground = true): static
    {
        $this->showBackground = $showBackground;

        return $this;
    }

    /** @param  array<string, mixed>  $backgroundStyle The style of the background bar drawn when `showBackground()` is enabled. */
    public function backgroundStyle(array $backgroundStyle): static
    {
        $this->backgroundStyle = $backgroundStyle;

        return $this;
    }

    /** Rounds the far end of each bar into a capsule shape (polar bar charts). */
    public function roundCap(bool $roundCap = true): static
    {
        $this->roundCap = $roundCap;

        return $this;
    }

    #[\Override]
    protected function build(): array
    {
        $series = array_merge(parent::build(), $this->dataArray(), $this->stackArray());
        if ($this->barWidth !== null) {
            $series['barWidth'] = $this->barWidth;
        }
        if ($this->barGap !== null) {
            $series['barGap'] = $this->barGap;
        }
        if ($this->barCategoryGap !== null) {
            $series['barCategoryGap'] = $this->barCategoryGap;
        }
        if ($this->showBackground !== null) {
            $series['showBackground'] = $this->showBackground;
        }
        if ($this->backgroundStyle !== null) {
            $series['backgroundStyle'] = $this->backgroundStyle;
        }
        if ($this->roundCap !== null) {
            $series['roundCap'] = $this->roundCap;
        }

        return $series;
    }
}
