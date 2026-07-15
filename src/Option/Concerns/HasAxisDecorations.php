<?php

namespace Happenv\FilamentEnhancedCharts\Option\Concerns;

use Happenv\FilamentEnhancedCharts\Option\Component\AxisPointer;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

/**
 * Visual/behavioral decorations shared by every axis-like builder: the
 * cartesian `Axis` base (`xAxis`/`yAxis`) and the non-cartesian axis
 * components (`AngleAxis`, `RadiusAxis`, `SingleAxis`). Cartesian-only
 * surface (`gridIndex`, `alignTicks`, `position`, `offset`, …) stays on
 * `Axis` itself.
 *
 * Decorations that differ per host class (`data`, `boundaryGap`,
 * `axisLabel`, `splitLine`, `interval`) stay on the host class and are
 * NOT part of this trait, to avoid redeclaration conflicts.
 */
trait HasAxisDecorations
{
    private ?bool $show = null;

    private ?int $z = null;

    private ?int $zlevel = null;

    private ?bool $silent = null;

    /** @var array<string, mixed>|null */
    private ?array $axisLine = null;

    /** @var array<string, mixed>|null */
    private ?array $axisTick = null;

    /** @var array<string, mixed>|null */
    private ?array $splitArea = null;

    /** @var array<string, mixed>|null */
    private ?array $minorTick = null;

    /** @var array<string, mixed>|null */
    private ?array $minorSplitLine = null;

    private int | float | null $minInterval = null;

    private int | float | null $maxInterval = null;

    private ?int $splitNumber = null;

    private int | float | null $nameGap = null;

    private ?string $nameLocation = null;

    /** @var array<string, mixed>|null */
    private ?array $nameTextStyle = null;

    private int | float | null $nameRotate = null;

    /** @var array<string, mixed>|null */
    private ?array $axisPointer = null;

    private ?bool $scale = null;

    public function show(bool $show = true): static
    {
        $this->show = $show;

        return $this;
    }

    /** Stacking order within the same `zlevel`. */
    public function z(int $z): static
    {
        $this->z = $z;

        return $this;
    }

    /** Stacking level of the canvas the axis is drawn on, below `z`. */
    public function zlevel(int $zlevel): static
    {
        $this->zlevel = $zlevel;

        return $this;
    }

    public function silent(bool $silent = true): static
    {
        $this->silent = $silent;

        return $this;
    }

    /**
     * The axis line. Pass `false` to hide it, or an array (`lineStyle`, etc.).
     *
     * @param  array<string, mixed>|bool  $axisLine
     */
    public function axisLine(array | bool $axisLine = true): static
    {
        $this->axisLine = is_bool($axisLine) ? ['show' => $axisLine] : $axisLine;

        return $this;
    }

    /** @param array<string, mixed>|bool $axisTick */
    public function axisTick(array | bool $axisTick = true): static
    {
        $this->axisTick = is_bool($axisTick) ? ['show' => $axisTick] : $axisTick;

        return $this;
    }

    /**
     * Alternating background bands between split lines. Pass `true`/an array
     * to show them (`areaStyle`, etc.); off by default.
     *
     * @param  array<string, mixed>|bool  $splitArea
     */
    public function splitArea(array | bool $splitArea = true): static
    {
        $this->splitArea = is_bool($splitArea) ? ['show' => $splitArea] : $splitArea;

        return $this;
    }

    /** @param array<string, mixed>|bool $minorTick */
    public function minorTick(array | bool $minorTick = true): static
    {
        $this->minorTick = is_bool($minorTick) ? ['show' => $minorTick] : $minorTick;

        return $this;
    }

    /** @param array<string, mixed>|bool $minorSplitLine */
    public function minorSplitLine(array | bool $minorSplitLine = true): static
    {
        $this->minorSplitLine = is_bool($minorSplitLine) ? ['show' => $minorSplitLine] : $minorSplitLine;

        return $this;
    }

    /** Sets a lower bound on the auto-computed label/tick interval (value axis). */
    public function minInterval(int | float $minInterval): static
    {
        $this->minInterval = $minInterval;

        return $this;
    }

    /** Sets an upper bound on the auto-computed label/tick interval (value axis). */
    public function maxInterval(int | float $maxInterval): static
    {
        $this->maxInterval = $maxInterval;

        return $this;
    }

    /** Number of segments the axis is split into (a hint — not exact for a category axis). */
    public function splitNumber(int $splitNumber): static
    {
        $this->splitNumber = $splitNumber;

        return $this;
    }

    /** Gap between the axis name and the axis line, in pixels. */
    public function nameGap(int | float $nameGap): static
    {
        $this->nameGap = $nameGap;

        return $this;
    }

    /** Where the axis name sits along the axis: 'start'|'middle'|'end'. */
    public function nameLocation(string $nameLocation): static
    {
        $this->nameLocation = $nameLocation;

        return $this;
    }

    /** @param Label|array<string, mixed> $nameTextStyle */
    public function nameTextStyle(Label | array $nameTextStyle): static
    {
        $this->nameTextStyle = Normalize::arr($nameTextStyle);

        return $this;
    }

    /** Rotation of the axis name, in degrees. */
    public function nameRotate(int | float $nameRotate): static
    {
        $this->nameRotate = $nameRotate;

        return $this;
    }

    /**
     * The axis pointer shown alongside a hovered value on this axis.
     *
     * @param  AxisPointer|array<string, mixed>|bool  $axisPointer
     */
    public function axisPointer(AxisPointer | array | bool $axisPointer = true): static
    {
        $this->axisPointer = is_bool($axisPointer) ? ['show' => $axisPointer] : Normalize::arr($axisPointer);

        return $this;
    }

    /** A value axis that does not force the scale to include zero. */
    public function scale(bool $scale = true): static
    {
        $this->scale = $scale;

        return $this;
    }

    /**
     * The set decoration keys, omitting unset ones. Merge into the host's
     * `toArray()`/`build()` array before applying `mergeRaw()`.
     *
     * @return array<string, mixed>
     */
    protected function axisDecorations(): array
    {
        $axis = [];

        if ($this->show !== null) {
            $axis['show'] = $this->show;
        }
        if ($this->z !== null) {
            $axis['z'] = $this->z;
        }
        if ($this->zlevel !== null) {
            $axis['zlevel'] = $this->zlevel;
        }
        if ($this->silent !== null) {
            $axis['silent'] = $this->silent;
        }
        if ($this->axisLine !== null) {
            $axis['axisLine'] = $this->axisLine;
        }
        if ($this->axisTick !== null) {
            $axis['axisTick'] = $this->axisTick;
        }
        if ($this->splitArea !== null) {
            $axis['splitArea'] = $this->splitArea;
        }
        if ($this->minorTick !== null) {
            $axis['minorTick'] = $this->minorTick;
        }
        if ($this->minorSplitLine !== null) {
            $axis['minorSplitLine'] = $this->minorSplitLine;
        }
        if ($this->minInterval !== null) {
            $axis['minInterval'] = $this->minInterval;
        }
        if ($this->maxInterval !== null) {
            $axis['maxInterval'] = $this->maxInterval;
        }
        if ($this->splitNumber !== null) {
            $axis['splitNumber'] = $this->splitNumber;
        }
        if ($this->nameGap !== null) {
            $axis['nameGap'] = $this->nameGap;
        }
        if ($this->nameLocation !== null) {
            $axis['nameLocation'] = $this->nameLocation;
        }
        if ($this->nameTextStyle !== null) {
            $axis['nameTextStyle'] = $this->nameTextStyle;
        }
        if ($this->nameRotate !== null) {
            $axis['nameRotate'] = $this->nameRotate;
        }
        if ($this->axisPointer !== null) {
            $axis['axisPointer'] = $this->axisPointer;
        }
        if ($this->scale !== null) {
            $axis['scale'] = $this->scale;
        }

        return $axis;
    }
}
