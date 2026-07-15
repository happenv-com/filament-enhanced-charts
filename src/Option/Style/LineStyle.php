<?php

namespace Happenv\FilamentEnhancedCharts\Option\Style;

use Happenv\FilamentEnhancedCharts\Enums\LineType;

final class LineStyle extends Style
{
    public function width(int | float $width): static
    {
        $this->properties['width'] = $width;

        return $this;
    }

    public function type(LineType | string $type): static
    {
        $this->properties['type'] = $type instanceof LineType ? $type->value : $type;

        return $this;
    }

    public function solid(): static
    {
        return $this->type('solid');
    }

    public function dashed(): static
    {
        return $this->type('dashed');
    }

    public function dotted(): static
    {
        return $this->type('dotted');
    }

    public function curveness(int | float $curveness): static
    {
        $this->properties['curveness'] = $curveness;

        return $this;
    }

    /** @param string $cap Line cap style: 'butt', 'round', or 'square'. */
    public function cap(string $cap): static
    {
        $this->properties['cap'] = $cap;

        return $this;
    }

    /** @param string $join Line join style: 'bevel', 'round', or 'miter'. */
    public function join(string $join): static
    {
        $this->properties['join'] = $join;

        return $this;
    }

    public function dashOffset(int | float $dashOffset): static
    {
        $this->properties['dashOffset'] = $dashOffset;

        return $this;
    }
}
