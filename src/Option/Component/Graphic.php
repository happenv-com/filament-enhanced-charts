<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component;

use Happenv\FilamentEnhancedCharts\Option\Component\Graphic\GraphicElement;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Illuminate\Support\Traits\Conditionable;

/**
 * Custom shapes (rect/circle/text/image/line/group, …) overlaid on the
 * chart, positioned independently of any coordinate system. Emitted as the
 * top-level `graphic` option key.
 */
final class Graphic implements Node
{
    use Conditionable;
    use HasRaw;

    /** @var list<GraphicElement|array<string, mixed>> */
    private array $elements = [];

    public static function make(): self
    {
        return new self;
    }

    /** @param iterable<GraphicElement|array<string, mixed>> $elements */
    public function elements(iterable $elements): self
    {
        $this->elements = Normalize::list($elements);

        return $this;
    }

    public function toArray(): array
    {
        return $this->mergeRaw(['elements' => Normalize::value($this->elements)]);
    }
}
