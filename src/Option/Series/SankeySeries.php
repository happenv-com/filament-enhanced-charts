<?php

namespace Happenv\FilamentEnhancedCharts\Option\Series;

use Happenv\FilamentEnhancedCharts\Enums\NodeAlign;
use Happenv\FilamentEnhancedCharts\Enums\Orient;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLayout;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLineStyle;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasSize;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

final class SankeySeries extends Series
{
    use HasLayout;
    use HasLineStyle;
    use HasSize;

    private ?string $layout = null;

    /** @var array<mixed>|null */
    private ?array $nodes = null;

    /** @var array<mixed>|null */
    private ?array $links = null;

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

    /**
     * Optional per-node configuration, matched to the derived nodes by name.
     * Node names come from the links (a node is a function of its edges), so a
     * node only needs listing here to carry value/depth/itemStyle/label. Any
     * configured node not referenced by a link is still included. Accepts
     * SankeyNode objects or raw arrays.
     *
     * @param  iterable<mixed>  $nodes
     */
    public function nodes(iterable $nodes): static
    {
        $this->nodes = Normalize::iterable($nodes);

        return $this;
    }

    /** @param iterable<mixed> $links */
    public function links(iterable $links): static
    {
        $this->links = Normalize::iterable($links);

        return $this;
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
        // data() array (it doesn't use the HasData trait), so `data` is built
        // from nodes/links below.
        $series = parent::build();

        $links = null;
        if ($this->links !== null) {
            $links = Normalize::value($this->links);
        }

        // Nodes are derived from the links (with any explicit config overlaid).
        $data = $this->buildNodes($links);
        if ($data !== []) {
            $series['data'] = $data;
        }
        if ($links !== null) {
            $series['links'] = $links;
        }

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

    /**
     * The node list: names come from the links (first-appearance order), with
     * any explicitly-configured node overlaid by name, plus any configured node
     * no link references. Empty when there are neither links nor explicit nodes.
     *
     * @param  list<array<string, mixed>>|null  $links  the normalized links
     * @return list<array<string, mixed>>
     */
    private function buildNodes(?array $links): array
    {
        $configByName = [];
        if ($this->nodes !== null) {
            foreach ($this->nodes as $item) {
                $node = Normalize::value($item);
                if (is_array($node) && isset($node['name'])) {
                    $configByName[$node['name']] = $node;
                }
            }
        }

        $data = [];
        $seen = [];
        foreach ($links ?? [] as $link) {
            foreach (['source', 'target'] as $end) {
                $name = $link[$end] ?? null;
                if ($name === null) {
                    continue;
                }
                if (isset($seen[$name])) {
                    continue;
                }
                $seen[$name] = true;
                $data[] = $configByName[$name] ?? ['name' => $name];
            }
        }

        foreach ($configByName as $name => $node) {
            if (! isset($seen[$name])) {
                $data[] = $node;
            }
        }

        return $data;
    }
}
