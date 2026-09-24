<?php

namespace Happenv\FilamentEnhancedCharts\Option\Concerns;

use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

/**
 * Nodes-and-links series (sankey, chord): the node list is derived from the
 * links, so a node only needs listing when it carries its own configuration.
 */
trait HasLinkedNodes
{
    /** @var array<mixed>|null */
    private ?array $nodes = null;

    /** @var array<mixed>|null */
    private ?array $links = null;

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

    /**
     * The weighted edges between nodes, as SankeyLink objects or raw
     * `['source' => …, 'target' => …, 'value' => …]` arrays.
     *
     * @param  iterable<mixed>  $links
     */
    public function links(iterable $links): static
    {
        $this->links = Normalize::iterable($links);

        return $this;
    }

    /**
     * The `data` (nodes) and `links` keys, omitting empty ones.
     *
     * @return array<string, mixed>
     */
    protected function linkedNodesArray(): array
    {
        $links = $this->links !== null ? Normalize::value($this->links) : null;

        $result = [];
        $data = $this->buildNodes($links);
        if ($data !== []) {
            $result['data'] = $data;
        }
        if ($links !== null) {
            $result['links'] = $links;
        }

        return $result;
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
