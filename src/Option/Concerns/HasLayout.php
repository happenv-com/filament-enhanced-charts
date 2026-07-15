<?php

namespace Happenv\FilamentEnhancedCharts\Option\Concerns;

/**
 * Rectangular positioning shared by box-layout series/components (sankey,
 * treemap, funnel, …): the distance from each edge of the container, given as
 * a pixel int or a percentage/keyword string (e.g. 8, '5%', 'center').
 */
trait HasLayout
{
    private int | string | null $left = null;

    private int | string | null $right = null;

    private int | string | null $top = null;

    private int | string | null $bottom = null;

    public function left(int | string $left): static
    {
        $this->left = $left;

        return $this;
    }

    public function right(int | string $right): static
    {
        $this->right = $right;

        return $this;
    }

    public function top(int | string $top): static
    {
        $this->top = $top;

        return $this;
    }

    public function bottom(int | string $bottom): static
    {
        $this->bottom = $bottom;

        return $this;
    }

    /**
     * The set edge offsets in left/right/top/bottom order, omitting unset ones.
     *
     * @return array<string, int|string>
     */
    protected function boxLayoutArray(): array
    {
        return array_filter(
            ['left' => $this->left, 'right' => $this->right, 'top' => $this->top, 'bottom' => $this->bottom],
            static fn (int | string | null $value): bool => $value !== null,
        );
    }
}
