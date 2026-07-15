<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasAxisLabel;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRadius;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Illuminate\Support\Traits\Conditionable;

final class Radar implements Node
{
    use Conditionable;
    use HasAxisLabel;
    use HasRadius;
    use HasRaw;

    /** @var list<array<string, mixed>> */
    private array $indicator = [];

    private ?string $shape = null;

    private ?int $splitNumber = null;

    private ?bool $scale = null;

    /** @var array<string, mixed>|null */
    private ?array $axisName = null;

    /** @var array<string, mixed>|bool|null */
    private array | bool | null $axisLine = null;

    /** @var array<string, mixed>|bool|null */
    private array | bool | null $splitLine = null;

    /** @var array<string, mixed>|bool|null */
    private array | bool | null $splitArea = null;

    private ?int $nameGap = null;

    private int | float | null $startAngle = null;

    public static function make(): self
    {
        return new self;
    }

    /** @param list<array<string, mixed>> $indicators */
    public function indicator(array $indicators): self
    {
        $this->indicator = $indicators;

        return $this;
    }

    /** @param string $shape ECharts' radar shape: 'polygon' or 'circle'. */
    public function shape(string $shape): self
    {
        $this->shape = $shape;

        return $this;
    }

    public function splitNumber(int $splitNumber): self
    {
        $this->splitNumber = $splitNumber;

        return $this;
    }

    /** Whether the axis scale is auto-adjusted to nicer round numbers rather than starting at 0. */
    public function scale(bool $scale = true): self
    {
        $this->scale = $scale;

        return $this;
    }

    /** @param array<string, mixed> $axisName A full axisName config (show/formatter/font, …). */
    public function axisName(array $axisName): self
    {
        $this->axisName = $axisName;

        return $this;
    }

    /** @param array<string, mixed>|bool $axisLine */
    public function axisLine(array | bool $axisLine = true): self
    {
        $this->axisLine = $axisLine;

        return $this;
    }

    /** @param array<string, mixed>|bool $splitLine */
    public function splitLine(array | bool $splitLine = true): self
    {
        $this->splitLine = $splitLine;

        return $this;
    }

    /** @param array<string, mixed>|bool $splitArea */
    public function splitArea(array | bool $splitArea = true): self
    {
        $this->splitArea = $splitArea;

        return $this;
    }

    /** The gap between an indicator's axis line and its name label (ECharts' `axisNameGap`). */
    public function nameGap(int $nameGap): self
    {
        $this->nameGap = $nameGap;

        return $this;
    }

    public function startAngle(int | float $angle): self
    {
        $this->startAngle = $angle;

        return $this;
    }

    public function toArray(): array
    {
        $radar = ['indicator' => Normalize::value($this->indicator)];

        if ($this->shape !== null) {
            $radar['shape'] = $this->shape;
        }
        if ($this->splitNumber !== null) {
            $radar['splitNumber'] = $this->splitNumber;
        }
        if ($this->scale !== null) {
            $radar['scale'] = $this->scale;
        }
        if ($this->axisName !== null) {
            $radar['axisName'] = $this->axisName;
        }
        $radar = array_merge($radar, $this->axisLabelArray());

        if ($this->axisLine !== null) {
            $radar['axisLine'] = is_array($this->axisLine) ? $this->axisLine : ['show' => $this->axisLine];
        }
        if ($this->splitLine !== null) {
            $radar['splitLine'] = is_array($this->splitLine) ? $this->splitLine : ['show' => $this->splitLine];
        }
        if ($this->splitArea !== null) {
            $radar['splitArea'] = is_array($this->splitArea) ? $this->splitArea : ['show' => $this->splitArea];
        }
        if ($this->nameGap !== null) {
            $radar['axisNameGap'] = $this->nameGap;
        }
        if ($this->startAngle !== null) {
            $radar['startAngle'] = $this->startAngle;
        }

        return $this->mergeRaw(array_merge($radar, $this->radiusLayout()));
    }
}
