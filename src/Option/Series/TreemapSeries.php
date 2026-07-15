<?php

namespace Happenv\FilamentEnhancedCharts\Option\Series;

use BcMath\Number;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasData;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLayout;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRoam;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasSize;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

final class TreemapSeries extends Series
{
    use HasData;
    use HasLayout;
    use HasRoam;
    use HasSize;

    private ?int $leafDepth = null;

    private ?int $visibleMin = null;

    /** @var array<string, mixed>|null */
    private ?array $upperLabel = null;

    /** @var array<int, mixed>|null */
    private ?array $levels = null;

    private int | float | string | Number | null $visualMin = null;

    private int | float | string | Number | null $visualMax = null;

    private ?int $visualDimension = null;

    private ?string $colorMappingBy = null;

    private bool | string | null $nodeClick = null;

    /** @var array<string, mixed>|null */
    private ?array $breadcrumb = null;

    protected function type(): string
    {
        return 'treemap';
    }

    public function leafDepth(int $depth): static
    {
        $this->leafDepth = $depth;

        return $this;
    }

    /** The minimum pixel area a node must occupy to render at all; smaller nodes are hidden. */
    public function visibleMin(int $visibleMin): static
    {
        $this->visibleMin = $visibleMin;

        return $this;
    }

    /**
     * The header label rendered above a node that has children, separate
     * from the leaf `label()`. `false` hides it; an array configures it directly.
     *
     * @param  array<string, mixed>|bool  $upperLabel
     */
    public function upperLabel(array | bool $upperLabel = true): static
    {
        $this->upperLabel = is_bool($upperLabel) ? ['show' => $upperLabel] : $upperLabel;

        return $this;
    }

    /**
     * Per-depth level overrides (color/itemStyle/gapWidth per level), e.g.
     * `[['itemStyle' => ['borderWidth' => 0, 'gapWidth' => 1]], ['itemStyle' => ['gapWidth' => 1]]]`.
     * No dedicated builder — pass the array directly.
     *
     * @param  array<int, mixed>  $levels
     */
    public function levels(array $levels): static
    {
        $this->levels = Normalize::value($levels);

        return $this;
    }

    /** The lower bound of the value range used to compute the visual (color) mapping. */
    public function visualMin(int | float | string | Number $visualMin): static
    {
        $this->visualMin = $visualMin;

        return $this;
    }

    /** The upper bound of the value range used to compute the visual (color) mapping. */
    public function visualMax(int | float | string | Number $visualMax): static
    {
        $this->visualMax = $visualMax;

        return $this;
    }

    /** Which data dimension (index) drives the visual (color) mapping. */
    public function visualDimension(int $visualDimension): static
    {
        $this->visualDimension = $visualDimension;

        return $this;
    }

    /** How a node's color is derived: `'value'`, `'index'`, or `'id'`. */
    public function colorMappingBy(string $colorMappingBy): static
    {
        $this->colorMappingBy = $colorMappingBy;

        return $this;
    }

    /** @param bool|string $nodeClick 'zoomToNode' (default) or 'link'; `false` disables clicking. */
    public function nodeClick(bool | string $nodeClick = true): static
    {
        $this->nodeClick = $nodeClick;

        return $this;
    }

    /**
     * The trail of ancestor nodes shown above the chart. `false` hides it;
     * an array configures it directly (`show`/`height`/`itemStyle`/…).
     *
     * @param  array<string, mixed>|bool  $breadcrumb
     */
    public function breadcrumb(array | bool $breadcrumb = true): static
    {
        $this->breadcrumb = is_bool($breadcrumb) ? ['show' => $breadcrumb] : $breadcrumb;

        return $this;
    }

    #[\Override]
    protected function build(): array
    {
        $series = array_merge(parent::build(), $this->dataArray(), $this->roamArray());
        if ($this->leafDepth !== null) {
            $series['leafDepth'] = $this->leafDepth;
        }
        if ($this->visibleMin !== null) {
            $series['visibleMin'] = $this->visibleMin;
        }
        if ($this->upperLabel !== null) {
            $series['upperLabel'] = $this->upperLabel;
        }
        if ($this->levels !== null) {
            $series['levels'] = $this->levels;
        }
        if ($this->visualMin !== null) {
            $series['visualMin'] = Normalize::value($this->visualMin);
        }
        if ($this->visualMax !== null) {
            $series['visualMax'] = Normalize::value($this->visualMax);
        }
        if ($this->visualDimension !== null) {
            $series['visualDimension'] = $this->visualDimension;
        }
        if ($this->colorMappingBy !== null) {
            $series['colorMappingBy'] = $this->colorMappingBy;
        }
        if ($this->nodeClick !== null) {
            $series['nodeClick'] = $this->nodeClick;
        }
        if ($this->breadcrumb !== null) {
            $series['breadcrumb'] = $this->breadcrumb;
        }
        $series = array_merge($series, $this->sizeArray());

        return array_merge($series, $this->boxLayoutArray());
    }
}
