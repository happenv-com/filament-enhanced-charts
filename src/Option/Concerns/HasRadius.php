<?php

namespace Happenv\FilamentEnhancedCharts\Option\Concerns;

/**
 * Circular positioning shared by centred series/components (pie, sunburst,
 * gauge, radar, …): the radius — a percent/pixel string, a number, or an
 * [inner, outer] pair — and the center, given as [x, y].
 */
trait HasRadius
{
    /** @var string|int|array<int, string|int>|null */
    private string | int | array | null $radius = null;

    /** @var array<int, string|int>|null */
    private ?array $center = null;

    /** @param string|int|array<int, string|int> $radius */
    public function radius(string | int | array $radius): static
    {
        $this->radius = $radius;

        return $this;
    }

    /** @param array<int, string|int> $center */
    public function center(array $center): static
    {
        $this->center = $center;

        return $this;
    }

    /**
     * The set radius/center keys, omitting unset ones.
     *
     * @return array<string, string|int|array<int, string|int>>
     */
    protected function radiusLayout(): array
    {
        $layout = [];
        if ($this->radius !== null) {
            $layout['radius'] = $this->radius;
        }
        if ($this->center !== null) {
            $layout['center'] = $this->center;
        }

        return $layout;
    }
}
