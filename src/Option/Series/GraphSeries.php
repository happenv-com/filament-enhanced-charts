<?php

namespace Happenv\FilamentEnhancedCharts\Option\Series;

use Happenv\FilamentEnhancedCharts\Enums\GraphLayout;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasData;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLayout;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLineStyle;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRoam;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasSize;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasSymbol;
use Happenv\FilamentEnhancedCharts\Option\Style\Force;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

/**
 * A force-directed or circular network graph: nodes (via `data()`) connected
 * by `links()`/`edges()`. Has its own layout algorithm (`layout()`/`force()`),
 * so — unlike cartesian/polar series — it needs no coordinate-system binding.
 */
final class GraphSeries extends Series
{
    use HasData;
    use HasLayout;
    use HasLineStyle;
    use HasRoam;
    use HasSize;
    use HasSymbol;

    private ?string $layout = null;

    private ?string $roamTrigger = null;

    /** @var array<string, mixed>|null */
    private ?array $scaleLimit = null;

    /** @var array<string, mixed>|null */
    private ?array $circular = null;

    private int | float | null $nodeScaleRatio = null;

    private ?bool $draggable = null;

    /** @var array<mixed>|null */
    private ?array $links = null;

    /** @var array<mixed>|null */
    private ?array $categories = null;

    /** @var array<string, mixed>|null */
    private ?array $force = null;

    private string | array | null $edgeSymbol = null;

    private int | array | null $edgeSymbolSize = null;

    /** @var array<string, mixed>|null */
    private ?array $edgeLabel = null;

    /** @var array<int, int|string>|null */
    private ?array $center = null;

    private int | float | null $zoom = null;

    protected function type(): string
    {
        return 'graph';
    }

    public function layout(GraphLayout | string $layout): static
    {
        $this->layout = Normalize::enum($layout);

        return $this;
    }

    /** The area that triggers roaming on hover: 'global' (entire canvas) or 'selfRect' (default). */
    public function roamTrigger(string $roamTrigger): static
    {
        $this->roamTrigger = $roamTrigger;

        return $this;
    }

    /** @param array<string, mixed> $scaleLimit The `roam` zoom bounds: `['min' => ..., 'max' => ...]`. */
    public function scaleLimit(array $scaleLimit): static
    {
        $this->scaleLimit = $scaleLimit;

        return $this;
    }

    /**
     * Circular-layout tuning (pairs with `layout(GraphLayout::Circular)`).
     * Pass `true`/`false` to toggle automatic label rotation, or a full array.
     *
     * @param  array<string, mixed>|bool  $circular
     */
    public function circular(array | bool $circular = true): static
    {
        $this->circular = is_bool($circular) ? ['rotateLabel' => $circular] : $circular;

        return $this;
    }

    /** Symbol size scale ratio applied while roaming (zooming). */
    public function nodeScaleRatio(int | float $nodeScaleRatio): static
    {
        $this->nodeScaleRatio = $nodeScaleRatio;

        return $this;
    }

    public function draggable(bool $draggable = true): static
    {
        $this->draggable = $draggable;

        return $this;
    }

    /**
     * Edges connecting nodes by name, e.g. `['source' => 'A', 'target' => 'B',
     * 'value' => 3, 'lineStyle' => [...]]`. Accepts raw arrays or `Node` builders.
     *
     * @param  iterable<mixed>  $links
     */
    public function links(iterable $links): static
    {
        $this->links = Normalize::iterable($links);

        return $this;
    }

    /** Alias for {@see links()}. @param iterable<mixed> $edges */
    public function edges(iterable $edges): static
    {
        return $this->links($edges);
    }

    /**
     * Legend groups nodes can be assigned to via their `category` key, e.g.
     * `['name' => 'Group A']`. Accepts raw arrays or `Node` builders.
     *
     * @param  iterable<mixed>  $categories
     */
    public function categories(iterable $categories): static
    {
        $this->categories = Normalize::iterable($categories);

        return $this;
    }

    /**
     * Tunes the `layout(GraphLayout::Force)` physics simulation.
     *
     * @param  Force|array<string, mixed>  $force
     */
    public function force(Force | array $force): static
    {
        $this->force = Normalize::arr($force);

        return $this;
    }

    /** @param string|array<int, string> $edgeSymbol A single symbol for both ends, or [source, target] (e.g. ['none', 'arrow']). */
    public function edgeSymbol(string | array $edgeSymbol): static
    {
        $this->edgeSymbol = $edgeSymbol;

        return $this;
    }

    /** @param int|array<int, int> $edgeSymbolSize A single size for both ends, or [source, target]. */
    public function edgeSymbolSize(int | array $edgeSymbolSize): static
    {
        $this->edgeSymbolSize = $edgeSymbolSize;

        return $this;
    }

    /** @param Label|array<string, mixed> $edgeLabel Styles edge labels. */
    public function edgeLabel(Label | array $edgeLabel): static
    {
        $this->edgeLabel = Normalize::arr($edgeLabel);

        return $this;
    }

    /** @param array<int, int|string> $center [x, y] as pixel numbers or percentage strings. */
    public function center(array $center): static
    {
        $this->center = $center;

        return $this;
    }

    public function zoom(int | float $zoom): static
    {
        $this->zoom = $zoom;

        return $this;
    }

    #[\Override]
    protected function build(): array
    {
        $series = array_merge(parent::build(), $this->dataArray());

        if ($this->layout !== null) {
            $series['layout'] = $this->layout;
        }
        $series = array_merge($series, $this->roamArray());
        if ($this->roamTrigger !== null) {
            $series['roamTrigger'] = $this->roamTrigger;
        }
        $series = array_merge($series, $this->sizeArray());
        if ($this->scaleLimit !== null) {
            $series['scaleLimit'] = $this->scaleLimit;
        }
        if ($this->circular !== null) {
            $series['circular'] = $this->circular;
        }
        if ($this->nodeScaleRatio !== null) {
            $series['nodeScaleRatio'] = $this->nodeScaleRatio;
        }
        if ($this->draggable !== null) {
            $series['draggable'] = $this->draggable;
        }
        if ($this->links !== null) {
            $series['links'] = Normalize::value($this->links);
        }
        if ($this->categories !== null) {
            $series['categories'] = Normalize::value($this->categories);
        }
        if ($this->force !== null) {
            $series['force'] = $this->force;
        }
        if ($this->edgeSymbol !== null) {
            $series['edgeSymbol'] = $this->edgeSymbol;
        }
        if ($this->edgeSymbolSize !== null) {
            $series['edgeSymbolSize'] = $this->edgeSymbolSize;
        }
        $series = array_merge($series, $this->symbolConfig());
        $series = array_merge($series, $this->lineStyleArray());
        if ($this->edgeLabel !== null) {
            $series['edgeLabel'] = $this->edgeLabel;
        }
        if ($this->center !== null) {
            $series['center'] = $this->center;
        }
        if ($this->zoom !== null) {
            $series['zoom'] = $this->zoom;
        }

        return array_merge($series, $this->boxLayoutArray());
    }
}
