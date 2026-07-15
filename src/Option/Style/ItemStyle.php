<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Option\Style;

use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

final class ItemStyle extends Style
{
    /** @param string|array<mixed> $color A CSS color, or a Filament palette (Color::Amber). */
    public function borderColor(string | array $color): static
    {
        $this->properties['borderColor'] = Normalize::color($color);

        return $this;
    }

    public function borderWidth(int | float $width): static
    {
        $this->properties['borderWidth'] = $width;

        return $this;
    }

    public function borderType(string $type): static
    {
        $this->properties['borderType'] = $type;

        return $this;
    }

    /** @param int|array<int, int> $radius A single radius or [tl, tr, br, bl]. */
    public function borderRadius(int | array $radius): static
    {
        $this->properties['borderRadius'] = $radius;

        return $this;
    }

    /**
     * Candlestick down-color (close < open) — the counterpart to `color()`,
     * which is the up-color (close >= open).
     *
     * @param  string|array<mixed>  $color  A CSS color, or a Filament palette (Color::Amber).
     */
    public function color0(string | array $color): static
    {
        $this->properties['color0'] = Normalize::color($color);

        return $this;
    }

    /**
     * Candlestick down-color border — the counterpart to `borderColor()`.
     *
     * @param  string|array<mixed>  $color  A CSS color, or a Filament palette (Color::Amber).
     */
    public function borderColor0(string | array $color): static
    {
        $this->properties['borderColor0'] = Normalize::color($color);

        return $this;
    }

    /**
     * Geo/map region fill color — the counterpart to `color()` used for map
     * and geo series regional area painting.
     *
     * @param  string|array<mixed>  $color  A CSS color, or a Filament palette (Color::Amber).
     */
    public function areaColor(string | array $color): static
    {
        $this->properties['areaColor'] = Normalize::color($color);

        return $this;
    }

    public function borderDashOffset(int $offset): static
    {
        $this->properties['borderDashOffset'] = $offset;

        return $this;
    }

    /** @param array<string, mixed> $decal ECharts decal (texture) pattern config: symbol, color, dashArrayX, … */
    public function decal(array $decal): static
    {
        $this->properties['decal'] = $decal;

        return $this;
    }
}
