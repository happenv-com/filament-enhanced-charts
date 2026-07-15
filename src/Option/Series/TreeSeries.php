<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Option\Series;

use Happenv\FilamentEnhancedCharts\Enums\TreeEdgeShape;
use Happenv\FilamentEnhancedCharts\Enums\TreeLayout;
use Happenv\FilamentEnhancedCharts\Enums\TreeOrient;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasData;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLayout;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLineStyle;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRoam;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasSymbol;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

/**
 * A hierarchical node-link tree: nested `data()` nodes (each an optional
 * `name`/`value`/`children`), drawn orthogonally or radially.
 */
final class TreeSeries extends Series
{
    use HasData;
    use HasLayout;
    use HasLineStyle;
    use HasRoam;
    use HasSymbol;

    private ?string $layout = null;

    private ?string $orient = null;

    private ?string $edgeShape = null;

    private ?string $edgeForkPosition = null;

    private ?bool $expandAndCollapse = null;

    private ?int $initialTreeDepth = null;

    /** @var array<string, mixed>|null */
    private ?array $leaves = null;

    protected function type(): string
    {
        return 'tree';
    }

    public function layout(TreeLayout | string $layout): static
    {
        $this->layout = Normalize::enum($layout);

        return $this;
    }

    /** The orthogonal layout's growth direction; ignored under a radial layout. */
    public function orient(TreeOrient | string $orient): static
    {
        $this->orient = Normalize::enum($orient);

        return $this;
    }

    public function edgeShape(TreeEdgeShape | string $edgeShape): static
    {
        $this->edgeShape = Normalize::enum($edgeShape);

        return $this;
    }

    /** Position of the fork point on a polyline edge (`edgeShape(TreeEdgeShape::Polyline)`), e.g. `'50%'`. */
    public function edgeForkPosition(string $edgeForkPosition): static
    {
        $this->edgeForkPosition = $edgeForkPosition;

        return $this;
    }

    /** Whether a node can be expanded/collapsed by clicking it; pairs with `initialTreeDepth()`. */
    public function expandAndCollapse(bool $expandAndCollapse = true): static
    {
        $this->expandAndCollapse = $expandAndCollapse;

        return $this;
    }

    /** The initially-expanded depth of the tree; deeper nodes start collapsed. */
    public function initialTreeDepth(int $depth): static
    {
        $this->initialTreeDepth = $depth;

        return $this;
    }

    /**
     * Label/style overrides applied only to leaf nodes (nodes with no
     * children), e.g. `['label' => ['position' => 'right']]`.
     *
     * @param  array<string, mixed>  $leaves
     */
    public function leaves(array $leaves): static
    {
        $this->leaves = $leaves;

        return $this;
    }

    #[\Override]
    protected function build(): array
    {
        $series = array_merge(parent::build(), $this->dataArray(), $this->symbolConfig());

        if ($this->layout !== null) {
            $series['layout'] = $this->layout;
        }
        if ($this->orient !== null) {
            $series['orient'] = $this->orient;
        }
        $series = array_merge($series, $this->roamArray());
        if ($this->edgeShape !== null) {
            $series['edgeShape'] = $this->edgeShape;
        }
        if ($this->edgeForkPosition !== null) {
            $series['edgeForkPosition'] = $this->edgeForkPosition;
        }
        if ($this->expandAndCollapse !== null) {
            $series['expandAndCollapse'] = $this->expandAndCollapse;
        }
        if ($this->initialTreeDepth !== null) {
            $series['initialTreeDepth'] = $this->initialTreeDepth;
        }
        if ($this->leaves !== null) {
            $series['leaves'] = $this->leaves;
        }
        $series = array_merge($series, $this->lineStyleArray());

        return array_merge($series, $this->boxLayoutArray());
    }
}
