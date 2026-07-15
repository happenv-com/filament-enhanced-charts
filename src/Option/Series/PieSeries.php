<?php

namespace Happenv\FilamentEnhancedCharts\Option\Series;

use Happenv\FilamentEnhancedCharts\Enums\RoseType;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasData;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLayout;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRadius;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasSize;

final class PieSeries extends Series
{
    use HasData;
    use HasLayout;
    use HasRadius;
    use HasSize;

    private string | bool | null $roseType = null;

    private int | float | null $padAngle = null;

    private ?bool $avoidLabelOverlap = null;

    private int | float | null $startAngle = null;

    private int | float | null $endAngle = null;

    private int | float | null $minAngle = null;

    private ?bool $clockwise = null;

    private int | float | null $selectedOffset = null;

    protected function type(): string
    {
        return 'pie';
    }

    /** @param  RoseType|bool|string  $roseType  `true` is shorthand for `RoseType::Radius`; `false` disables it. */
    public function roseType(RoseType | bool | string $roseType): static
    {
        $this->roseType = match (true) {
            $roseType instanceof RoseType => $roseType->value,
            is_bool($roseType) => $roseType ? RoseType::Radius->value : false,
            default => $roseType,
        };

        return $this;
    }

    /** The pie's start angle in degrees (0 points right/east, angles increase counter-clockwise). */
    public function startAngle(int | float $startAngle): static
    {
        $this->startAngle = $startAngle;

        return $this;
    }

    /** The pie's end angle in degrees — pair with `startAngle()` to draw a partial pie. */
    public function endAngle(int | float $endAngle): static
    {
        $this->endAngle = $endAngle;

        return $this;
    }

    /** The minimum angle a slice may have, in degrees — keeps tiny values visible/clickable. */
    public function minAngle(int | float $minAngle): static
    {
        $this->minAngle = $minAngle;

        return $this;
    }

    public function clockwise(bool $clockwise = true): static
    {
        $this->clockwise = $clockwise;

        return $this;
    }

    /** Pixel offset of a selected slice from the pie's center. Pair with `selectedMode()`. */
    public function selectedOffset(int | float $selectedOffset): static
    {
        $this->selectedOffset = $selectedOffset;

        return $this;
    }

    /** The angle gap between adjacent slices, in degrees. */
    public function padAngle(int | float $padAngle): static
    {
        $this->padAngle = $padAngle;

        return $this;
    }

    public function avoidLabelOverlap(bool $avoidLabelOverlap = true): static
    {
        $this->avoidLabelOverlap = $avoidLabelOverlap;

        return $this;
    }

    #[\Override]
    protected function build(): array
    {
        $series = array_merge(parent::build(), $this->dataArray(), $this->radiusLayout());
        if ($this->roseType !== null) {
            $series['roseType'] = $this->roseType;
        }
        if ($this->padAngle !== null) {
            $series['padAngle'] = $this->padAngle;
        }
        if ($this->avoidLabelOverlap !== null) {
            $series['avoidLabelOverlap'] = $this->avoidLabelOverlap;
        }
        if ($this->startAngle !== null) {
            $series['startAngle'] = $this->startAngle;
        }
        if ($this->endAngle !== null) {
            $series['endAngle'] = $this->endAngle;
        }
        if ($this->minAngle !== null) {
            $series['minAngle'] = $this->minAngle;
        }
        if ($this->clockwise !== null) {
            $series['clockwise'] = $this->clockwise;
        }
        if ($this->selectedOffset !== null) {
            $series['selectedOffset'] = $this->selectedOffset;
        }
        $series = array_merge($series, $this->sizeArray());

        return array_merge($series, $this->boxLayoutArray());
    }
}
