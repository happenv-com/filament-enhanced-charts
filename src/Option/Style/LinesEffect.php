<?php

namespace Happenv\FilamentEnhancedCharts\Option\Style;

use Happenv\FilamentEnhancedCharts\Enums\Symbol;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Illuminate\Support\Traits\Conditionable;

/**
 * The "flight path" trail animation drawn along a `LinesSeries` line — a
 * moving symbol (optionally trailing a fading tail) that loops along each
 * line's coords. Composed via `LinesSeries::effect()`.
 */
final class LinesEffect implements Node
{
    use Conditionable;
    use HasRaw;

    private ?bool $show = null;

    private int | float | null $period = null;

    private int | float | null $trailLength = null;

    private ?string $symbol = null;

    private int | float | null $symbolSize = null;

    private string | array | null $color = null;

    private int | float | null $constantSpeed = null;

    public static function make(): self
    {
        return new self;
    }

    public function show(bool $show = true): self
    {
        $this->show = $show;

        return $this;
    }

    /** Animation duration in seconds for one loop along the line. Ignored once constantSpeed() is set. */
    public function period(int | float $period): self
    {
        $this->period = $period;

        return $this;
    }

    /** Trail length, from 0 (no trail, a bare moving symbol) to 1 (trail spans the whole line). */
    public function trailLength(int | float $trailLength): self
    {
        $this->trailLength = $trailLength;

        return $this;
    }

    public function symbol(Symbol | string $symbol): self
    {
        $this->symbol = Normalize::enum($symbol);

        return $this;
    }

    public function symbolSize(int | float $size): self
    {
        $this->symbolSize = $size;

        return $this;
    }

    /**
     * Defaults to the line's own `lineStyle` color when unset.
     *
     * @param  string|array<mixed>  $color  A CSS color, or a Filament palette (Color::Amber).
     */
    public function color(string | array $color): self
    {
        $this->color = Normalize::color($color);

        return $this;
    }

    /** A constant px/sec speed; overrides period() once set. */
    public function constantSpeed(int | float $constantSpeed): self
    {
        $this->constantSpeed = $constantSpeed;

        return $this;
    }

    public function toArray(): array
    {
        $effect = [];

        if ($this->show !== null) {
            $effect['show'] = $this->show;
        }
        if ($this->period !== null) {
            $effect['period'] = $this->period;
        }
        if ($this->trailLength !== null) {
            $effect['trailLength'] = $this->trailLength;
        }
        if ($this->symbol !== null) {
            $effect['symbol'] = $this->symbol;
        }
        if ($this->symbolSize !== null) {
            $effect['symbolSize'] = $this->symbolSize;
        }
        if ($this->color !== null) {
            $effect['color'] = $this->color;
        }
        if ($this->constantSpeed !== null) {
            $effect['constantSpeed'] = $this->constantSpeed;
        }

        return $this->mergeRaw($effect);
    }
}
