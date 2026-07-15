<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component;

use Happenv\FilamentEnhancedCharts\Enums\AxisType;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasAxisDecorations;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasAxisLabel;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasAxisRange;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Illuminate\Support\Traits\Conditionable;

/**
 * The angle (circular) axis of a polar coordinate system. Not an entry of
 * `xAxis`/`yAxis` — it hosts on a `Polar` component, addressed by
 * `polarIndex()`.
 */
final class AngleAxis implements Node
{
    use Conditionable;
    use HasAxisDecorations;
    use HasAxisLabel;
    use HasAxisRange;
    use HasRaw;

    private ?string $type = null;

    private int | float | null $startAngle = null;

    private int | float | null $endAngle = null;

    private ?bool $clockwise = null;

    /** @var array<mixed>|null */
    private ?array $data = null;

    private ?int $polarIndex = null;

    public static function make(): self
    {
        return new self;
    }

    public function type(AxisType | string $type): self
    {
        $this->type = Normalize::enum($type);

        return $this;
    }

    public function startAngle(int | float $angle): self
    {
        $this->startAngle = $angle;

        return $this;
    }

    public function endAngle(int | float $angle): self
    {
        $this->endAngle = $angle;

        return $this;
    }

    public function clockwise(bool $clockwise = true): self
    {
        $this->clockwise = $clockwise;

        return $this;
    }

    /** @param iterable<mixed> $data */
    public function data(iterable $data): self
    {
        $this->data = Normalize::iterable($data);

        return $this;
    }

    public function polarIndex(int $index): self
    {
        $this->polarIndex = $index;

        return $this;
    }

    public function toArray(): array
    {
        $axis = [];

        if ($this->type !== null) {
            $axis['type'] = $this->type;
        }
        if ($this->startAngle !== null) {
            $axis['startAngle'] = $this->startAngle;
        }
        if ($this->endAngle !== null) {
            $axis['endAngle'] = $this->endAngle;
        }
        if ($this->clockwise !== null) {
            $axis['clockwise'] = $this->clockwise;
        }

        $axis = array_merge($axis, $this->axisRange());

        if ($this->data !== null) {
            $axis['data'] = Normalize::value($this->data);
        }

        $axis = array_merge($axis, $this->axisLabelArray());

        if ($this->polarIndex !== null) {
            $axis['polarIndex'] = $this->polarIndex;
        }

        $axis = array_merge($axis, $this->axisDecorations());

        return $this->mergeRaw($axis);
    }
}
