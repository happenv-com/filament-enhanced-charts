<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLayout;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRoam;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Happenv\FilamentEnhancedCharts\Option\Style\Emphasis;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Illuminate\Support\Traits\Conditionable;

/**
 * A standalone `geo` coordinate component: a registered map rendered on its
 * own, decoupled from any `series.map`. Series (scatter, lines, effectScatter,
 * …) bind to it via `geoIndex()`. The map itself (GeoJSON) must be registered
 * client-side via `echarts.registerMap()` before this option is applied.
 */
final class Geo implements Node
{
    use Conditionable;
    use HasLayout;
    use HasRaw;
    use HasRoam;

    private ?string $map = null;

    private int | float | null $zoom = null;

    /** @var array<int, string|int>|null */
    private ?array $center = null;

    /** @var array<string, mixed>|null */
    private ?array $label = null;

    /** @var array<string, mixed>|null */
    private ?array $itemStyle = null;

    /** @var array<string, mixed>|null */
    private ?array $emphasis = null;

    /** @var array<string, mixed>|null */
    private ?array $select = null;

    private ?string $nameProperty = null;

    /** @var array<int, string|int>|null */
    private ?array $layoutCenter = null;

    private int | string | null $layoutSize = null;

    /** @var list<array<string, mixed>>|null */
    private ?array $regions = null;

    private ?bool $silent = null;

    public static function make(): self
    {
        return new self;
    }

    /**
     * The registered map name this component renders (e.g. 'USA', 'world').
     * Must be registered client-side via `echarts.registerMap(name, geoJson)`
     * before this option is applied.
     */
    public function map(string $map): self
    {
        $this->map = $map;

        return $this;
    }

    /** Current zoom level of the map. Default is 1. */
    public function zoom(int | float $zoom): self
    {
        $this->zoom = $zoom;

        return $this;
    }

    /** @param array<int, string|int> $center Current center position, as [x, y]. */
    public function center(array $center): self
    {
        $this->center = $center;

        return $this;
    }

    /** @param Label|array<string, mixed> $label */
    public function label(Label | array $label): self
    {
        $this->label = Normalize::arr($label);

        return $this;
    }

    /** @param ItemStyle|array<string, mixed> $itemStyle */
    public function itemStyle(ItemStyle | array $itemStyle): self
    {
        $this->itemStyle = Normalize::arr($itemStyle);

        return $this;
    }

    /** @param Emphasis|array<string, mixed> $emphasis */
    public function emphasis(Emphasis | array $emphasis): self
    {
        $this->emphasis = Normalize::arr($emphasis);

        return $this;
    }

    /**
     * The style applied to a region while selected (requires `selectedMode`
     * true, 'single', or 'multiple' via a region's own config, see `regions()`).
     * e.g. `['itemStyle' => ItemStyle::make()->color('#c23531')]`.
     *
     * @param  array<string, mixed>  $select
     */
    public function select(array $select): self
    {
        $this->select = $select;

        return $this;
    }

    /** The data-item key that names a region when matching the registered map's GeoJSON features. */
    public function nameProperty(string $nameProperty): self
    {
        $this->nameProperty = $nameProperty;

        return $this;
    }

    /** @param array<int, string|int> $layoutCenter The center of the map, used with `layoutSize`. */
    public function layoutCenter(array $layoutCenter): self
    {
        $this->layoutCenter = $layoutCenter;

        return $this;
    }

    /** Zoom level used with `layoutCenter` for scaling relative to the container. */
    public function layoutSize(int | string $layoutSize): self
    {
        $this->layoutSize = $layoutSize;

        return $this;
    }

    /**
     * Per-region overrides (name, style, tooltip, selection state, …).
     *
     * @param  iterable<array<string, mixed>>  $regions
     */
    public function regions(iterable $regions): self
    {
        $this->regions = Normalize::list($regions);

        return $this;
    }

    /** Disable all mouse/touch interaction and events on the map. */
    public function silent(bool $silent = true): self
    {
        $this->silent = $silent;

        return $this;
    }

    public function toArray(): array
    {
        $geo = [];

        if ($this->map !== null) {
            $geo['map'] = $this->map;
        }
        $geo = array_merge($geo, $this->roamArray());
        if ($this->zoom !== null) {
            $geo['zoom'] = $this->zoom;
        }
        if ($this->center !== null) {
            $geo['center'] = $this->center;
        }
        if ($this->label !== null) {
            $geo['label'] = $this->label;
        }
        if ($this->itemStyle !== null) {
            $geo['itemStyle'] = $this->itemStyle;
        }
        if ($this->emphasis !== null) {
            $geo['emphasis'] = $this->emphasis;
        }
        if ($this->select !== null) {
            $geo['select'] = Normalize::value($this->select);
        }
        if ($this->nameProperty !== null) {
            $geo['nameProperty'] = $this->nameProperty;
        }
        if ($this->silent !== null) {
            $geo['silent'] = $this->silent;
        }

        $geo = array_merge($geo, $this->boxLayoutArray());

        if ($this->layoutCenter !== null) {
            $geo['layoutCenter'] = $this->layoutCenter;
        }
        if ($this->layoutSize !== null) {
            $geo['layoutSize'] = $this->layoutSize;
        }
        if ($this->regions !== null) {
            $geo['regions'] = Normalize::value($this->regions);
        }

        return $this->mergeRaw($geo);
    }
}
