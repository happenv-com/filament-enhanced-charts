<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component;

use Happenv\FilamentEnhancedCharts\Enums\Orient;
use Happenv\FilamentEnhancedCharts\Option\Component\Toolbox\ToolboxFeature;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLayout;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Illuminate\Support\Traits\Conditionable;

/**
 * The built-in toolbox (save-as-image, restore, data view, data zoom, magic
 * type, brush icons). Individual tools are built with the `ToolboxFeature`
 * subclasses in `Option\Component\Toolbox\*` and registered via `feature()`.
 */
final class Toolbox implements Node
{
    use Conditionable;
    use HasLayout;
    use HasRaw;

    private ?bool $show = null;

    private ?string $orient = null;

    private ?int $itemSize = null;

    private ?int $itemGap = null;

    private ?bool $showTitle = null;

    /** @var array<string, mixed>|null */
    private ?array $iconStyle = null;

    /** @var array<string, mixed>|null */
    private ?array $emphasis = null;

    /** @var array<string, array<string, mixed>>|null */
    private ?array $feature = null;

    public static function make(): self
    {
        return new self;
    }

    public function show(bool $show = true): self
    {
        $this->show = $show;

        return $this;
    }

    public function orient(Orient | string $orient): self
    {
        $this->orient = Normalize::enum($orient);

        return $this;
    }

    public function itemSize(int $itemSize): self
    {
        $this->itemSize = $itemSize;

        return $this;
    }

    public function itemGap(int $itemGap): self
    {
        $this->itemGap = $itemGap;

        return $this;
    }

    public function showTitle(bool $showTitle = true): self
    {
        $this->showTitle = $showTitle;

        return $this;
    }

    /** @param array<string, mixed> $iconStyle */
    public function iconStyle(array $iconStyle): self
    {
        $this->iconStyle = $iconStyle;

        return $this;
    }

    /** @param array<string, mixed> $emphasis */
    public function emphasis(array $emphasis): self
    {
        $this->emphasis = $emphasis;

        return $this;
    }

    public function feature(ToolboxFeature ...$features): self
    {
        $this->feature ??= [];

        foreach ($features as $feature) {
            $this->feature[$feature->featureKey()] = $feature->toArray();
        }

        return $this;
    }

    public function toArray(): array
    {
        $toolbox = [];

        if ($this->show !== null) {
            $toolbox['show'] = $this->show;
        }
        if ($this->orient !== null) {
            $toolbox['orient'] = $this->orient;
        }
        if ($this->itemSize !== null) {
            $toolbox['itemSize'] = $this->itemSize;
        }
        if ($this->itemGap !== null) {
            $toolbox['itemGap'] = $this->itemGap;
        }
        if ($this->showTitle !== null) {
            $toolbox['showTitle'] = $this->showTitle;
        }
        if ($this->iconStyle !== null) {
            $toolbox['iconStyle'] = $this->iconStyle;
        }
        if ($this->emphasis !== null) {
            $toolbox['emphasis'] = $this->emphasis;
        }
        if ($this->feature !== null) {
            $toolbox['feature'] = $this->feature;
        }

        return $this->mergeRaw(array_merge($toolbox, $this->boxLayoutArray()));
    }
}
