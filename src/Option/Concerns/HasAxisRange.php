<?php

namespace Happenv\FilamentEnhancedCharts\Option\Concerns;

use BcMath\Number;
use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

/**
 * The scale/range knobs shared by every axis-like builder (`Axis`,
 * `AngleAxis`, `RadiusAxis`, `SingleAxis`): min/max, boundaryGap, the
 * forced label/tick interval and the splitLine.
 */
trait HasAxisRange
{
    private int | float | string | Number | null $min = null;

    private int | float | string | Number | null $max = null;

    /** @var bool|array<int, mixed>|null */
    private bool | array | null $boundaryGap = null;

    private int | float | RawJs | null $interval = null;

    /** @var array<string, mixed>|bool|null */
    private array | bool | null $splitLine = null;

    public function min(int | float | string | Number $min): static
    {
        $this->min = $min;

        return $this;
    }

    public function max(int | float | string | Number $max): static
    {
        $this->max = $max;

        return $this;
    }

    /**
     * Category axes take a bool; value axes take a `['10%', '20%']` pair.
     *
     * @param  bool|array<int, mixed>  $boundaryGap
     */
    public function boundaryGap(bool | array $boundaryGap = true): static
    {
        $this->boundaryGap = $boundaryGap;

        return $this;
    }

    /** Forces the label/tick interval instead of letting ECharts compute it. */
    public function interval(int | float | RawJs $interval): static
    {
        $this->interval = $interval;

        return $this;
    }

    /**
     * The split line drawn across the plotting area at each tick. Pass
     * `false` to hide it, or an array (`lineStyle`, etc.).
     *
     * @param  array<string, mixed>|bool  $splitLine
     */
    public function splitLine(array | bool $splitLine = true): static
    {
        $this->splitLine = $splitLine;

        return $this;
    }

    /**
     * The set range keys, omitting unset ones.
     *
     * @return array<string, mixed>
     */
    protected function axisRange(): array
    {
        $axis = [];

        if ($this->min !== null) {
            $axis['min'] = Normalize::value($this->min);
        }
        if ($this->max !== null) {
            $axis['max'] = Normalize::value($this->max);
        }
        if ($this->boundaryGap !== null) {
            $axis['boundaryGap'] = is_array($this->boundaryGap)
                ? Normalize::value($this->boundaryGap)
                : $this->boundaryGap;
        }
        if ($this->interval !== null) {
            $axis['interval'] = Normalize::value($this->interval);
        }
        if ($this->splitLine !== null) {
            $axis['splitLine'] = is_bool($this->splitLine) ? ['show' => $this->splitLine] : $this->splitLine;
        }

        return $axis;
    }
}
