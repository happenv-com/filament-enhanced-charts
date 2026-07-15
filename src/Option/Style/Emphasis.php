<?php

namespace Happenv\FilamentEnhancedCharts\Option\Style;

use Happenv\FilamentEnhancedCharts\Enums\Focus;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Illuminate\Support\Traits\Conditionable;

/**
 * The hover/emphasis state: a focus mode plus the styles to apply while
 * emphasised. Composes the leaf styles rather than duplicating them.
 */
final class Emphasis implements Node
{
    use Conditionable;
    use HasRaw;

    private ?bool $disabled = null;

    private ?string $focus = null;

    /** @var array<string, mixed>|null */
    private ?array $itemStyle = null;

    /** @var array<string, mixed>|null */
    private ?array $lineStyle = null;

    /** @var array<string, mixed>|null */
    private ?array $areaStyle = null;

    /** @var array<string, mixed>|null */
    private ?array $label = null;

    private ?bool $scale = null;

    public static function make(): self
    {
        return new self;
    }

    /** Turn the hover/emphasis state off entirely. */
    public function disabled(bool $disabled = true): self
    {
        $this->disabled = $disabled;

        return $this;
    }

    public function focus(Focus | string $focus): self
    {
        $this->focus = Normalize::enum($focus);

        return $this;
    }

    /** @param  ItemStyle|array<string, mixed>  $itemStyle */
    public function itemStyle(ItemStyle | array $itemStyle): self
    {
        $this->itemStyle = Normalize::arr($itemStyle);

        return $this;
    }

    /** @param  LineStyle|array<string, mixed>  $lineStyle */
    public function lineStyle(LineStyle | array $lineStyle): self
    {
        $this->lineStyle = Normalize::arr($lineStyle);

        return $this;
    }

    /** @param  AreaStyle|array<string, mixed>  $areaStyle */
    public function areaStyle(AreaStyle | array $areaStyle): self
    {
        $this->areaStyle = Normalize::arr($areaStyle);

        return $this;
    }

    /** @param  Label|array<string, mixed>  $label */
    public function label(Label | array $label): self
    {
        $this->label = Normalize::arr($label);

        return $this;
    }

    /** Scale up the hovered element (e.g. a pie slice) while emphasised. */
    public function scale(bool $scale = true): self
    {
        $this->scale = $scale;

        return $this;
    }

    public function toArray(): array
    {
        $emphasis = [];
        if ($this->disabled !== null) {
            $emphasis['disabled'] = $this->disabled;
        }
        if ($this->focus !== null) {
            $emphasis['focus'] = $this->focus;
        }
        if ($this->itemStyle !== null) {
            $emphasis['itemStyle'] = $this->itemStyle;
        }
        if ($this->lineStyle !== null) {
            $emphasis['lineStyle'] = $this->lineStyle;
        }
        if ($this->areaStyle !== null) {
            $emphasis['areaStyle'] = $this->areaStyle;
        }
        if ($this->label !== null) {
            $emphasis['label'] = $this->label;
        }
        if ($this->scale !== null) {
            $emphasis['scale'] = $this->scale;
        }

        return $this->mergeRaw($emphasis);
    }
}
