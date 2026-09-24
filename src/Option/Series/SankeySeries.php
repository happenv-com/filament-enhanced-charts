<?php

namespace Happenv\FilamentEnhancedCharts\Option\Series;

use Happenv\FilamentEnhancedCharts\Enums\NodeAlign;
use Happenv\FilamentEnhancedCharts\Enums\Orient;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLayout;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLineStyle;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLinkedNodes;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasSize;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

final class SankeySeries extends Series
{
    use HasLayout;
    use HasLineStyle;
    use HasLinkedNodes;
    use HasSize;

    private ?string $layout = null;

    private ?string $orient = null;

    private ?string $nodeAlign = null;

    private int | float | null $nodeGap = null;

    private int | float | null $nodeWidth = null;

    private ?int $layoutIterations = null;

    private ?bool $draggable = null;

    /** @var array<int, mixed>|null */
    private ?array $levels = null;

    /** @var array<string, mixed>|null */
    private ?array $edgeLabel = null;

    protected function type(): string
    {
        return 'sankey';
    }

    /** Flow direction of the diagram: 'horizontal' (default) or 'vertical'. */
    public function orient(Orient | string $orient): static
    {
        $this->orient = Normalize::enum($orient);

        return $this;
    }

    public function nodeAlign(NodeAlign | string $nodeAlign): static
    {
        $this->nodeAlign = Normalize::enum($nodeAlign);

        return $this;
    }

    /** The layout algorithm — ECharts currently documents only 'none' (reserved for future values). */
    public function layout(string $layout): static
    {
        $this->layout = $layout;

        return $this;
    }

    /** The gap between two adjacent nodes in the same column. */
    public function nodeGap(int | float $nodeGap): static
    {
        $this->nodeGap = $nodeGap;

        return $this;
    }

    /** The rectangle width of every node. */
    public function nodeWidth(int | float $nodeWidth): static
    {
        $this->nodeWidth = $nodeWidth;

        return $this;
    }

    /** Iterations spent untangling the node layout; higher settles cleaner at more cost. */
    public function layoutIterations(int $layoutIterations): static
    {
        $this->layoutIterations = $layoutIterations;

        return $this;
    }

    /** Whether a node can be dragged to reposition it. */
    public function draggable(bool $draggable = true): static
    {
        $this->draggable = $draggable;

        return $this;
    }

    /**
     * Per-depth (column) overrides — color/itemStyle/lineStyle/label per
     * depth. No dedicated builder — pass the array directly.
     *
     * @param  array<int, mixed>  $levels
     */
    public function levels(array $levels): static
    {
        $this->levels = Normalize::value($levels);

        return $this;
    }

    /** @param array<string, mixed> $edgeLabel Label shown on a link (edge) between two nodes. */
    public function edgeLabel(array $edgeLabel): static
    {
        $this->edgeLabel = $edgeLabel;

        return $this;
    }

    #[\Override]
    protected function build(): array
    {
        // Everything shared (id/name/color/z/tooltip/animation/…) comes from the
        // base; sankey carries nodes as `data` + a separate `links`, not a flat
        // data() array — the nodes are derived from the links (HasLinkedNodes).
        $series = array_merge(parent::build(), $this->linkedNodesArray());

        if ($this->layout !== null) {
            $series['layout'] = $this->layout;
        }
        if ($this->orient !== null) {
            $series['orient'] = $this->orient;
        }
        if ($this->nodeAlign !== null) {
            $series['nodeAlign'] = $this->nodeAlign;
        }
        if ($this->nodeGap !== null) {
            $series['nodeGap'] = $this->nodeGap;
        }
        if ($this->nodeWidth !== null) {
            $series['nodeWidth'] = $this->nodeWidth;
        }
        if ($this->layoutIterations !== null) {
            $series['layoutIterations'] = $this->layoutIterations;
        }
        if ($this->draggable !== null) {
            $series['draggable'] = $this->draggable;
        }
        if ($this->levels !== null) {
            $series['levels'] = $this->levels;
        }
        $series = array_merge($series, $this->lineStyleArray());
        if ($this->edgeLabel !== null) {
            $series['edgeLabel'] = $this->edgeLabel;
        }
        $series = array_merge($series, $this->sizeArray());

        return array_merge($series, $this->boxLayoutArray());
    }
}
