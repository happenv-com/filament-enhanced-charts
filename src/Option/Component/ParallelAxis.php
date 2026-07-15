<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component;

use BcMath\Number;
use Happenv\FilamentEnhancedCharts\Enums\AxisType;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasAxisLabel;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Illuminate\Support\Traits\Conditionable;

final class ParallelAxis implements Node
{
    use Conditionable;
    use HasAxisLabel;
    use HasRaw;

    private ?int $dim = null;

    private ?string $name = null;

    private ?string $type = null;

    private int | float | string | Number | null $min = null;

    private int | float | string | Number | null $max = null;

    private ?bool $inverse = null;

    /** @var array<mixed>|null */
    private ?array $data = null;

    private ?string $nameLocation = null;

    private ?bool $scale = null;

    public static function make(): self
    {
        return new self;
    }

    public function dim(int $dim): self
    {
        $this->dim = $dim;

        return $this;
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function type(AxisType | string $type): self
    {
        $this->type = Normalize::enum($type);

        return $this;
    }

    public function min(int | float | string | Number $min): self
    {
        $this->min = $min;

        return $this;
    }

    public function max(int | float | string | Number $max): self
    {
        $this->max = $max;

        return $this;
    }

    public function inverse(bool $inverse = true): self
    {
        $this->inverse = $inverse;

        return $this;
    }

    /** @param iterable<mixed> $data */
    public function data(iterable $data): self
    {
        $this->data = Normalize::list($data);

        return $this;
    }

    public function nameLocation(string $location): self
    {
        $this->nameLocation = $location;

        return $this;
    }

    /** Do not force the axis range to include zero; fit it to the data's min/max instead. */
    public function scale(bool $scale = true): self
    {
        $this->scale = $scale;

        return $this;
    }

    public function toArray(): array
    {
        $axis = [];
        if ($this->dim !== null) {
            $axis['dim'] = $this->dim;
        }
        if ($this->name !== null) {
            $axis['name'] = $this->name;
        }
        if ($this->type !== null) {
            $axis['type'] = $this->type;
        }
        if ($this->min !== null) {
            $axis['min'] = Normalize::value($this->min);
        }
        if ($this->max !== null) {
            $axis['max'] = Normalize::value($this->max);
        }
        if ($this->inverse !== null) {
            $axis['inverse'] = $this->inverse;
        }
        if ($this->data !== null) {
            $axis['data'] = Normalize::value($this->data);
        }
        if ($this->nameLocation !== null) {
            $axis['nameLocation'] = $this->nameLocation;
        }

        $axis = array_merge($axis, $this->axisLabelArray());

        if ($this->scale !== null) {
            $axis['scale'] = $this->scale;
        }

        return $this->mergeRaw($axis);
    }
}
