<?php

namespace Happenv\FilamentEnhancedCharts\Option\Style;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Illuminate\Support\Traits\Conditionable;

/**
 * Shared leaf-style properties (color, opacity, shadow) for the concrete shape
 * styles — ItemStyle, LineStyle, AreaStyle. Concrete subclasses add their own
 * setters; everything unmodelled is reachable through ->raw().
 */
abstract class Style implements Node
{
    use Conditionable;
    use HasRaw;

    /** @var array<string, mixed> */
    protected array $properties = [];

    public static function make(): static
    {
        return new static;
    }

    /** @param string|array<mixed> $color A CSS color, or a Filament palette (Color::Amber). */
    public function color(string | array $color): static
    {
        $this->properties['color'] = Normalize::color($color);

        return $this;
    }

    public function opacity(int | float $opacity): static
    {
        $this->properties['opacity'] = $opacity;

        return $this;
    }

    /** @param string|array<mixed>|null $color A CSS color, or a Filament palette (Color::Amber). */
    public function shadow(int $blur, string | array | null $color = null): static
    {
        $this->properties['shadowBlur'] = $blur;
        if ($color !== null) {
            $this->properties['shadowColor'] = Normalize::color($color);
        }

        return $this;
    }

    public function shadowOffsetX(int | float $offsetX): static
    {
        $this->properties['shadowOffsetX'] = $offsetX;

        return $this;
    }

    public function shadowOffsetY(int | float $offsetY): static
    {
        $this->properties['shadowOffsetY'] = $offsetY;

        return $this;
    }

    /** @param string|array<mixed> $color A CSS color, or a Filament palette (Color::Amber). */
    public function shadowColor(string | array $color): static
    {
        $this->properties['shadowColor'] = Normalize::color($color);

        return $this;
    }

    final public function toArray(): array
    {
        return $this->mergeRaw($this->properties);
    }
}
