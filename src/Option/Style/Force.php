<?php

namespace Happenv\FilamentEnhancedCharts\Option\Style;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Illuminate\Support\Traits\Conditionable;

/**
 * Force-directed layout tuning for a graph series (`GraphSeries::force()`):
 * node repulsion/gravity and edge length for the simulated physics layout.
 * A tuning cluster, not a shape style, so it does not share the Style base.
 */
final class Force implements Node
{
    use Conditionable;
    use HasRaw;

    /** @var array<string, mixed> */
    private array $properties = [];

    public static function make(): self
    {
        return new self;
    }

    /** Repulsion factor between nodes; a larger value pushes nodes further apart. */
    public function repulsion(int | float $repulsion): self
    {
        $this->properties['repulsion'] = $repulsion;

        return $this;
    }

    /** Gravity factor pulling all nodes toward the center. */
    public function gravity(int | float $gravity): self
    {
        $this->properties['gravity'] = $gravity;

        return $this;
    }

    /** @param int|float|array<int, int|float> $edgeLength A single ideal length, or [min, max]. */
    public function edgeLength(int | float | array $edgeLength): self
    {
        $this->properties['edgeLength'] = $edgeLength;

        return $this;
    }

    /** Whether the layout keeps animating/simulating after the initial layout settles. */
    public function layoutAnimation(bool $layoutAnimation = true): self
    {
        $this->properties['layoutAnimation'] = $layoutAnimation;

        return $this;
    }

    public function toArray(): array
    {
        return $this->mergeRaw($this->properties);
    }
}
