<?php

namespace Happenv\FilamentEnhancedCharts\Option\Concerns;

/**
 * The `width`/`height` box-layout pair shared by series/components that can be
 * sized explicitly (pie, funnel, graph, sankey, treemap, legend, grid, …).
 * A pixel int or a percentage string.
 */
trait HasSize
{
    private int | string | null $width = null;

    private int | string | null $height = null;

    public function width(int | string $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function height(int | string $height): static
    {
        $this->height = $height;

        return $this;
    }

    /**
     * The set width/height keys, omitting unset ones.
     *
     * @return array<string, int|string>
     */
    protected function sizeArray(): array
    {
        $size = [];
        if ($this->width !== null) {
            $size['width'] = $this->width;
        }
        if ($this->height !== null) {
            $size['height'] = $this->height;
        }

        return $size;
    }
}
