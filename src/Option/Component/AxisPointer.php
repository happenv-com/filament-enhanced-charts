<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component;

use Happenv\FilamentEnhancedCharts\Enums\AxisPointerType;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;
use Happenv\FilamentEnhancedCharts\Option\Style\LineStyle;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Illuminate\Support\Traits\Conditionable;

/**
 * The axis pointer shown alongside a hovered value: a `line`, a `shadow`
 * band, or a `cross` spanning both axes of a grid. Attach it via
 * `Axis::axisPointer()` or `Tooltip::axisPointer()`.
 */
final class AxisPointer implements Node
{
    use Conditionable;
    use HasRaw;

    private ?bool $show = null;

    private ?string $type = null;

    private ?bool $snap = null;

    /** @var array<string, mixed>|null */
    private ?array $label = null;

    /** @var array<string, mixed>|null */
    private ?array $lineStyle = null;

    /** @var array<string, mixed>|null */
    private ?array $shadowStyle = null;

    private ?bool $triggerTooltip = null;

    private mixed $value = null;

    private ?string $status = null;

    /** @var array<string, mixed>|null */
    private ?array $handle = null;

    /** @var array<int, array<string, mixed>>|null */
    private ?array $link = null;

    public static function make(): self
    {
        return new self;
    }

    public function show(bool $show = true): self
    {
        $this->show = $show;

        return $this;
    }

    public function type(AxisPointerType | string $type): self
    {
        $this->type = Normalize::enum($type);

        return $this;
    }

    /** Snaps to the nearest data point instead of following the cursor exactly. */
    public function snap(bool $snap = true): self
    {
        $this->snap = $snap;

        return $this;
    }

    /** @param Label|array<string, mixed> $label */
    public function label(Label | array $label): self
    {
        $this->label = Normalize::arr($label);

        return $this;
    }

    /** @param LineStyle|array<string, mixed> $lineStyle */
    public function lineStyle(LineStyle | array $lineStyle): self
    {
        $this->lineStyle = Normalize::arr($lineStyle);

        return $this;
    }

    /** @param array<string, mixed> $shadowStyle */
    public function shadowStyle(array $shadowStyle): self
    {
        $this->shadowStyle = $shadowStyle;

        return $this;
    }

    /** Whether hovering the pointer also triggers the tooltip (axis-triggered tooltips only). */
    public function triggerTooltip(bool $triggerTooltip = true): self
    {
        $this->triggerTooltip = $triggerTooltip;

        return $this;
    }

    /** Fixes the pointer at a specific axis value instead of following the cursor. */
    public function value(mixed $value): self
    {
        $this->value = $value;

        return $this;
    }

    /** Initial visibility of a fixed-`value` pointer: 'show'|'hide'. */
    /**
     * Link axis pointers across multiple grids/charts so they move together,
     * e.g. `[['xAxisIndex' => 'all']]`.
     *
     * @param  array<int, array<string, mixed>>  $link
     */
    public function link(array $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function status(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /** The draggable handle shown on the axis pointer (`icon`, `size`, `color`, …). */
    public function handle(array $handle): self
    {
        $this->handle = $handle;

        return $this;
    }

    public function toArray(): array
    {
        $axisPointer = [];

        if ($this->show !== null) {
            $axisPointer['show'] = $this->show;
        }
        if ($this->type !== null) {
            $axisPointer['type'] = $this->type;
        }
        if ($this->snap !== null) {
            $axisPointer['snap'] = $this->snap;
        }
        if ($this->label !== null) {
            $axisPointer['label'] = $this->label;
        }
        if ($this->lineStyle !== null) {
            $axisPointer['lineStyle'] = $this->lineStyle;
        }
        if ($this->shadowStyle !== null) {
            $axisPointer['shadowStyle'] = $this->shadowStyle;
        }
        if ($this->triggerTooltip !== null) {
            $axisPointer['triggerTooltip'] = $this->triggerTooltip;
        }
        if ($this->value !== null) {
            $axisPointer['value'] = Normalize::value($this->value);
        }
        if ($this->status !== null) {
            $axisPointer['status'] = $this->status;
        }
        if ($this->link !== null) {
            $axisPointer['link'] = $this->link;
        }
        if ($this->handle !== null) {
            $axisPointer['handle'] = $this->handle;
        }

        return $this->mergeRaw($axisPointer);
    }
}
