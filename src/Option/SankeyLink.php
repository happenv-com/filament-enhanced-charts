<?php

namespace Happenv\FilamentEnhancedCharts\Option;

use BcMath\Number;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Happenv\FilamentEnhancedCharts\Option\Style\LineStyle;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Illuminate\Support\Traits\Conditionable;

/**
 * A weighted edge in a Sankey diagram: `value` units flow from `source` to
 * `target` (both node names). Value accepts a native \BcMath\Number.
 */
final class SankeyLink implements Node
{
    use Conditionable;
    use HasRaw;

    /** @var array<string, mixed> */
    private array $properties;

    private function __construct(string $source, string $target)
    {
        $this->properties = ['source' => $source, 'target' => $target];
    }

    public static function make(string $source, string $target, int | float | string | Number | null $value = null): self
    {
        $link = new self($source, $target);

        if ($value !== null) {
            $link->properties['value'] = $value;
        }

        return $link;
    }

    public function value(int | float | string | Number $value): self
    {
        $this->properties['value'] = $value;

        return $this;
    }

    /** @param LineStyle|array<string, mixed> $lineStyle */
    public function lineStyle(LineStyle | array $lineStyle): self
    {
        $this->properties['lineStyle'] = Normalize::arr($lineStyle);

        return $this;
    }

    public function toArray(): array
    {
        return $this->mergeRaw(Normalize::value($this->properties));
    }
}
