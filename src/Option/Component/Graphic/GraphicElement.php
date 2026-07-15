<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component\Graphic;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLayout;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Illuminate\Support\Traits\Conditionable;

/**
 * Common positioning/interaction properties shared by every `graphic`
 * element (rect, circle, text, image, line, group, …). Concrete subclasses
 * add only their own shape/content setters; `toArray()` emits
 * `{type: elementType(), ...}` merged with `->raw()`.
 */
abstract class GraphicElement implements Node
{
    use Conditionable;
    use HasLayout;
    use HasRaw;

    private ?string $id = null;

    private ?int $z = null;

    private ?int $zlevel = null;

    private int | float | null $rotation = null;

    private int | float | null $scaleX = null;

    private int | float | null $scaleY = null;

    /** @var array<int, mixed>|null */
    private ?array $origin = null;

    private ?string $cursor = null;

    private ?bool $draggable = null;

    private ?bool $silent = null;

    private ?bool $invisible = null;

    private ?string $bounding = null;

    /** @var array<string, mixed> */
    private array $style = [];

    abstract protected function elementType(): string;

    public static function make(): static
    {
        return new static;
    }

    public function id(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function z(int $z): static
    {
        $this->z = $z;

        return $this;
    }

    public function zlevel(int $zlevel): static
    {
        $this->zlevel = $zlevel;

        return $this;
    }

    public function rotation(int | float $rotation): static
    {
        $this->rotation = $rotation;

        return $this;
    }

    public function scaleX(int | float $scaleX): static
    {
        $this->scaleX = $scaleX;

        return $this;
    }

    public function scaleY(int | float $scaleY): static
    {
        $this->scaleY = $scaleY;

        return $this;
    }

    /**
     * The rotate/scale center, e.g. `[0.5, 0.5]` (fraction of the element's
     * own bounding box).
     *
     * @param  array<int, mixed>  $origin
     */
    public function origin(array $origin): static
    {
        $this->origin = $origin;

        return $this;
    }

    public function cursor(string $cursor): static
    {
        $this->cursor = $cursor;

        return $this;
    }

    public function draggable(bool $draggable = true): static
    {
        $this->draggable = $draggable;

        return $this;
    }

    public function silent(bool $silent = true): static
    {
        $this->silent = $silent;

        return $this;
    }

    public function invisible(bool $invisible = true): static
    {
        $this->invisible = $invisible;

        return $this;
    }

    /**
     * How the bounding rect is computed when locating this element via a
     * percentage/keyword position: `'all'` (default) unions and transforms
     * every descendant's rect; `'raw'` uses only this element's own,
     * untransformed rect.
     */
    public function bounding(string $bounding): static
    {
        $this->bounding = $bounding;

        return $this;
    }

    /**
     * Merges into the element's `style` (fill/stroke/font/…, the exact keys
     * are shape-dependent). Repeated calls merge rather than replace, so
     * subclass convenience setters (e.g. `GraphicText::text()`) can layer on
     * top of a raw `style()` call in either order.
     *
     * @param  array<string, mixed>  $style
     */
    public function style(array $style): static
    {
        $this->style = array_merge($this->style, $style);

        return $this;
    }

    final public function toArray(): array
    {
        return $this->mergeRaw($this->build());
    }

    /** @return array<string, mixed> */
    protected function build(): array
    {
        $element = array_merge(['type' => $this->elementType()], $this->boxLayoutArray());

        if ($this->id !== null) {
            $element['id'] = $this->id;
        }
        if ($this->z !== null) {
            $element['z'] = $this->z;
        }
        if ($this->zlevel !== null) {
            $element['zlevel'] = $this->zlevel;
        }
        if ($this->rotation !== null) {
            $element['rotation'] = $this->rotation;
        }
        if ($this->scaleX !== null) {
            $element['scaleX'] = $this->scaleX;
        }
        if ($this->scaleY !== null) {
            $element['scaleY'] = $this->scaleY;
        }
        if ($this->origin !== null) {
            $element['origin'] = $this->origin;
        }
        if ($this->cursor !== null) {
            $element['cursor'] = $this->cursor;
        }
        if ($this->draggable !== null) {
            $element['draggable'] = $this->draggable;
        }
        if ($this->silent !== null) {
            $element['silent'] = $this->silent;
        }
        if ($this->invisible !== null) {
            $element['invisible'] = $this->invisible;
        }
        if ($this->bounding !== null) {
            $element['bounding'] = $this->bounding;
        }
        if ($this->style !== []) {
            $element['style'] = $this->style;
        }

        return $element;
    }
}
