<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component;

use Happenv\FilamentEnhancedCharts\Enums\AxisType;
use Happenv\FilamentEnhancedCharts\Enums\Orient;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasAxisDecorations;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasAxisLabel;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasAxisRange;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLayout;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasSize;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Illuminate\Support\Traits\Conditionable;

/**
 * A standalone coordinate system with a single axis, used by series that
 * don't sit on a cartesian grid (e.g. `ThemeRiverSeries`). Not an entry of
 * `xAxis`/`yAxis` — series bind to it via `singleAxisIndex()`.
 */
final class SingleAxis implements Node
{
    use Conditionable;
    use HasAxisDecorations;
    use HasAxisLabel;
    use HasAxisRange;
    use HasLayout;
    use HasRaw;
    use HasSize;

    private ?string $type = null;

    /** @var array<mixed>|null */
    private ?array $data = null;

    private ?string $orient = null;

    private ?bool $inverse = null;

    public static function make(): self
    {
        return new self;
    }

    public function type(AxisType | string $type): self
    {
        $this->type = Normalize::enum($type);

        return $this;
    }

    /** @param iterable<mixed> $data */
    public function data(iterable $data): self
    {
        $this->data = Normalize::iterable($data);

        return $this;
    }

    public function orient(Orient | string $orient): self
    {
        $this->orient = Normalize::enum($orient);

        return $this;
    }

    public function inverse(bool $inverse = true): self
    {
        $this->inverse = $inverse;

        return $this;
    }

    public function toArray(): array
    {
        $axis = [];

        if ($this->type !== null) {
            $axis['type'] = $this->type;
        }

        $axis = array_merge($axis, $this->axisRange());

        if ($this->data !== null) {
            $axis['data'] = Normalize::value($this->data);
        }

        $axis = array_merge($axis, $this->axisLabelArray());

        if ($this->orient !== null) {
            $axis['orient'] = $this->orient;
        }
        if ($this->inverse !== null) {
            $axis['inverse'] = $this->inverse;
        }

        $axis = array_merge($axis, $this->boxLayoutArray(), $this->axisDecorations(), $this->sizeArray());

        return $this->mergeRaw($axis);
    }
}
