<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;
use Happenv\FilamentEnhancedCharts\Option\Style\LineStyle;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Illuminate\Support\Traits\Conditionable;

/**
 * One header axis (`x` or `y`) of a `Matrix` coordinate system: the leaf
 * labels/groups that make up the column/row headers, how much space each
 * header level occupies, and its label/divider styling. Build independently
 * and pass to `Matrix::x()`/`Matrix::y()`.
 */
final class MatrixDimension implements Node
{
    use Conditionable;
    use HasRaw;

    /** @var array<mixed>|null */
    private ?array $data = null;

    private int | string | null $levelSize = null;

    /** @var array<string, mixed>|null */
    private ?array $label = null;

    private ?bool $show = null;

    /** @var array<string, mixed>|null */
    private ?array $dividerLineStyle = null;

    /** @var array<string, mixed>|null */
    private ?array $itemStyle = null;

    /** @var list<array<string, mixed>>|null */
    private ?array $levels = null;

    public static function make(): self
    {
        return new self;
    }

    /**
     * Header cells: bare labels (`['Q1', 'Q2']`) or `{value, size, children}`
     * shaped entries for grouped/nested headers
     * (`['value' => 'Q1', 'children' => ['Jan', 'Feb']]`).
     *
     * @param  iterable<mixed>  $data
     */
    public function data(iterable $data): self
    {
        $this->data = Normalize::list($data);

        return $this;
    }

    /** The pixel/percentage size this dimension's header band occupies. */
    public function levelSize(int | string $size): self
    {
        $this->levelSize = $size;

        return $this;
    }

    public function label(Label | array $label): self
    {
        $this->label = Normalize::arr($label);

        return $this;
    }

    public function show(bool $show = true): self
    {
        $this->show = $show;

        return $this;
    }

    public function dividerLineStyle(LineStyle | array $style): self
    {
        $this->dividerLineStyle = Normalize::arr($style);

        return $this;
    }

    /** @param ItemStyle|array<string, mixed> $itemStyle The default cell style for this dimension's header. */
    public function itemStyle(ItemStyle | array $itemStyle): self
    {
        $this->itemStyle = Normalize::arr($itemStyle);

        return $this;
    }

    /**
     * Per-level overrides for a nested/grouped header, one entry per depth
     * (e.g. `[['itemStyle' => [...]], ['itemStyle' => [...]]]`).
     *
     * @param  array<int, array<string, mixed>>  $levels
     */
    public function levels(array $levels): self
    {
        $this->levels = $levels;

        return $this;
    }

    public function toArray(): array
    {
        $dim = [];

        if ($this->show !== null) {
            $dim['show'] = $this->show;
        }
        if ($this->data !== null) {
            $dim['data'] = Normalize::value($this->data);
        }
        if ($this->levelSize !== null) {
            $dim['levelSize'] = $this->levelSize;
        }
        if ($this->label !== null) {
            $dim['label'] = $this->label;
        }
        if ($this->dividerLineStyle !== null) {
            $dim['dividerLineStyle'] = $this->dividerLineStyle;
        }
        if ($this->itemStyle !== null) {
            $dim['itemStyle'] = $this->itemStyle;
        }
        if ($this->levels !== null) {
            $dim['levels'] = Normalize::value($this->levels);
        }

        return $this->mergeRaw($dim);
    }
}
