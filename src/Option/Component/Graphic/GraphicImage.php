<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component\Graphic;

final class GraphicImage extends GraphicElement
{
    protected function elementType(): string
    {
        return 'image';
    }

    /** Convenience for `->style(['image' => $url])` — the image URL or data URI. */
    public function image(string $url): static
    {
        return $this->style(['image' => $url]);
    }

    /** Convenience for `->style(['width' => $width])`. */
    public function width(int | float $width): static
    {
        return $this->style(['width' => $width]);
    }

    /** Convenience for `->style(['height' => $height])`. */
    public function height(int | float $height): static
    {
        return $this->style(['height' => $height]);
    }
}
