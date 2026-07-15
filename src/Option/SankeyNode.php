<?php

namespace Happenv\FilamentEnhancedCharts\Option;

use BcMath\Number;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Illuminate\Support\Traits\Conditionable;

/**
 * A node in a Sankey diagram: a named box the flows pass through, with optional
 * value/depth/style overrides.
 */
final class SankeyNode implements Node
{
    use Conditionable;
    use HasRaw;

    /** @var array<string, mixed> */
    private array $properties;

    private function __construct(string $name)
    {
        $this->properties = ['name' => $name];
    }

    public static function make(string $name): self
    {
        return new self($name);
    }

    public function value(int | float | string | Number $value): self
    {
        $this->properties['value'] = $value;

        return $this;
    }

    public function depth(int $depth): self
    {
        $this->properties['depth'] = $depth;

        return $this;
    }

    /** @param ItemStyle|array<string, mixed> $itemStyle */
    public function itemStyle(ItemStyle | array $itemStyle): self
    {
        $this->properties['itemStyle'] = Normalize::arr($itemStyle);

        return $this;
    }

    /** @param Label|array<string, mixed> $label */
    public function label(Label | array $label): self
    {
        $this->properties['label'] = Normalize::arr($label);

        return $this;
    }

    public function toArray(): array
    {
        return $this->mergeRaw(Normalize::value($this->properties));
    }
}
