<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRadius;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Illuminate\Support\Traits\Conditionable;

/**
 * A polar coordinate system for series plotted by angle + radius. Pair with
 * an `AngleAxis` and a `RadiusAxis`; series bind to it via the base
 * `Series::coordinateSystem('polar')` + `Series::polarIndex()`.
 */
final class Polar implements Node
{
    use Conditionable;
    use HasRadius;
    use HasRaw;

    public static function make(): self
    {
        return new self;
    }

    public function toArray(): array
    {
        return $this->mergeRaw($this->radiusLayout());
    }
}
