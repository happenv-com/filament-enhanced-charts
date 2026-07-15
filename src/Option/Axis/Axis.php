<?php

namespace Happenv\FilamentEnhancedCharts\Option\Axis;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasAxisDecorations;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasAxisLabel;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasAxisRange;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Illuminate\Support\Traits\Conditionable;

abstract class Axis implements Node
{
    use Conditionable;
    use HasAxisDecorations;
    use HasAxisLabel;
    use HasAxisRange;
    use HasRaw;

    protected ?string $name = null;

    protected ?string $position = null;

    protected int | float | null $offset = null;

    protected ?bool $alignTicks = null;

    protected ?bool $inverse = null;

    protected ?int $gridIndex = null;

    protected int | float | null $jitter = null;

    protected ?bool $jitterOverlap = null;

    protected int | float | null $jitterMargin = null;

    abstract protected function type(): string;

    public static function make(): static
    {
        return new static;
    }

    public function name(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /** Aligns this axis's ticks with the ticks of the other axis in the grid. */
    public function alignTicks(bool $alignTicks = true): static
    {
        $this->alignTicks = $alignTicks;

        return $this;
    }

    /** Where the axis sits relative to the grid: 'top'|'bottom'|'left'|'right'. */
    public function position(string $position): static
    {
        $this->position = $position;

        return $this;
    }

    /** Offsets the axis away from its default position, in pixels. */
    public function offset(int | float $offset): static
    {
        $this->offset = $offset;

        return $this;
    }

    public function inverse(bool $inverse = true): static
    {
        $this->inverse = $inverse;

        return $this;
    }

    /** Places this axis in a specific grid (for multi-grid charts). */
    public function gridIndex(int $gridIndex): static
    {
        $this->gridIndex = $gridIndex;

        return $this;
    }

    /** Max jitter (px) applied along this axis to spread overlapping single-value points. */
    public function jitter(int | float $jitter): static
    {
        $this->jitter = $jitter;

        return $this;
    }

    /** Whether jittered points may still overlap (`true`) or are pushed apart (`false`). */
    public function jitterOverlap(bool $jitterOverlap = true): static
    {
        $this->jitterOverlap = $jitterOverlap;

        return $this;
    }

    /** Minimal gap (px) kept between jittered points when jitterOverlap is false. */
    public function jitterMargin(int | float $jitterMargin): static
    {
        $this->jitterMargin = $jitterMargin;

        return $this;
    }

    final public function toArray(): array
    {
        return $this->mergeRaw($this->build());
    }

    /** @return array<string, mixed> */
    protected function build(): array
    {
        $axis = ['type' => $this->type()];

        if ($this->name !== null) {
            $axis['name'] = $this->name;
        }

        $axis = array_merge($axis, $this->axisRange(), $this->axisLabelArray());

        if ($this->inverse !== null) {
            $axis['inverse'] = $this->inverse;
        }
        if ($this->gridIndex !== null) {
            $axis['gridIndex'] = $this->gridIndex;
        }
        if ($this->position !== null) {
            $axis['position'] = $this->position;
        }
        if ($this->offset !== null) {
            $axis['offset'] = $this->offset;
        }
        if ($this->alignTicks !== null) {
            $axis['alignTicks'] = $this->alignTicks;
        }
        if ($this->jitter !== null) {
            $axis['jitter'] = $this->jitter;
        }
        if ($this->jitterOverlap !== null) {
            $axis['jitterOverlap'] = $this->jitterOverlap;
        }
        if ($this->jitterMargin !== null) {
            $axis['jitterMargin'] = $this->jitterMargin;
        }

        // The shared axis decorations (show/z/axisLine/ticks/name*/…).
        return array_merge($axis, $this->axisDecorations());
    }
}
