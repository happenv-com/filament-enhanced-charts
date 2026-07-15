<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component\Graphic;

final class GraphicText extends GraphicElement
{
    protected function elementType(): string
    {
        return 'text';
    }

    /** Convenience for `->style(['text' => $text])`. */
    public function text(string $text): static
    {
        return $this->style(['text' => $text]);
    }

    /** Convenience for `->style(['font' => $font])` (a CSS font shorthand, e.g. '14px sans-serif'). */
    public function font(string $font): static
    {
        return $this->style(['font' => $font]);
    }

    /** Convenience for `->style(['fill' => $fill])` — the text color. */
    public function textFill(string $fill): static
    {
        return $this->style(['fill' => $fill]);
    }
}
