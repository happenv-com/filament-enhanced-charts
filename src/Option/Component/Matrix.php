<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLayout;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Illuminate\Support\Traits\Conditionable;

/**
 * The Matrix coordinate system (ECharts 6): a table-like grid built from an
 * `x`/`y` header pair (each a `MatrixDimension`), a `body`, and a top-left
 * `corner`. A series binds to it via `Series::coordinateSystem('matrix')`
 * (or `CoordinateSystem::Matrix`) and addresses a body/corner cell through
 * `->raw(['coord' => [...]])` — a first-class `coord()` setter on `Series` is
 * out of scope here.
 */
final class Matrix implements Node
{
    use Conditionable;
    use HasLayout;
    use HasRaw;

    /** @var array<string, mixed>|null */
    private ?array $x = null;

    /** @var array<string, mixed>|null */
    private ?array $y = null;

    /** @var array<string, mixed>|null */
    private ?array $corner = null;

    /** @var array<string, mixed>|null */
    private ?array $body = null;

    private int | string | null $width = null;

    private int | string | null $height = null;

    /** @var string|array<mixed>|null */
    private string | array | null $backgroundColor = null;

    public static function make(): self
    {
        return new self;
    }

    public function x(MatrixDimension | array $x): self
    {
        $this->x = Normalize::value(Normalize::arr($x));

        return $this;
    }

    public function y(MatrixDimension | array $y): self
    {
        $this->y = Normalize::value(Normalize::arr($y));

        return $this;
    }

    /**
     * The top-left header corner: `data` (specific cell definitions, located
     * by negative `coord` locators) plus shared cell style.
     *
     * @param  array<string, mixed>  $corner
     */
    public function corner(array $corner): self
    {
        $this->corner = Normalize::value($corner);

        return $this;
    }

    /**
     * The matrix body: `data` (specific cell definitions, located by `coord`)
     * plus shared cell style.
     *
     * @param  array<string, mixed>  $body
     */
    public function body(array $body): self
    {
        $this->body = Normalize::value($body);

        return $this;
    }

    public function width(int | string $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function height(int | string $height): self
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Shorthand for `backgroundStyle.color` — the fill behind the whole
     * matrix (header + body). Use `->raw(['backgroundStyle' => [...]])` for
     * the border/shadow siblings of `backgroundStyle`.
     *
     * @param  string|array<mixed>  $color  A CSS color, or a Filament palette (Color::Amber).
     */
    public function backgroundColor(string | array $color): self
    {
        $this->backgroundColor = Normalize::color($color);

        return $this;
    }

    public function toArray(): array
    {
        $matrix = [];

        if ($this->x !== null) {
            $matrix['x'] = $this->x;
        }
        if ($this->y !== null) {
            $matrix['y'] = $this->y;
        }
        if ($this->corner !== null) {
            $matrix['corner'] = $this->corner;
        }
        if ($this->body !== null) {
            $matrix['body'] = $this->body;
        }
        if ($this->backgroundColor !== null) {
            $matrix['backgroundStyle'] = ['color' => $this->backgroundColor];
        }

        $matrix = array_merge($matrix, $this->boxLayoutArray());

        if ($this->width !== null) {
            $matrix['width'] = $this->width;
        }
        if ($this->height !== null) {
            $matrix['height'] = $this->height;
        }

        return $this->mergeRaw($matrix);
    }
}
