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
 * The radius (radial) axis of a polar coordinate system. Not an entry of
 * `xAxis`/`yAxis` — it hosts on a `Polar` component, addressed by
 * `polarIndex()`.
 */
final class RadiusAxis implements Node
{
    use Conditionable;
    use HasAxisDecorations;
    use HasAxisLabel;
    use HasAxisRange;
    use HasRaw;

    private ?string $type = null;

    private ?string $name = null;

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

    public function name(string $name): self
    {
        $this->name = $name;

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
        if ($this->name !== null) {
            $axis['name'] = $this->name;
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
