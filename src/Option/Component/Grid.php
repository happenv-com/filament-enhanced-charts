<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLayout;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Illuminate\Support\Traits\Conditionable;

final class Grid implements Node
{
    use Conditionable;
    use HasLayout;
    use HasRaw;

    private ?bool $containLabel = null;

    private ?bool $show = null;

    private int | string | null $width = null;

    private int | string | null $height = null;

    /** @var string|array<mixed>|null */
    private string | array | null $backgroundColor = null;

    /** @var string|array<mixed>|null */
    private string | array | null $borderColor = null;

    private int | float | null $borderWidth = null;

    /** @var string|array<mixed>|null */
    private string | array | null $shadowColor = null;

    private int | float | null $shadowBlur = null;

    private int | float | null $shadowOffsetX = null;

    private int | float | null $shadowOffsetY = null;

    private ?int $zlevel = null;

    private ?int $z = null;

    public static function make(): self
    {
        return new self;
    }

    public function containLabel(bool $contain = true): self
    {
        $this->containLabel = $contain;

        return $this;
    }

    public function show(bool $show = true): self
    {
        $this->show = $show;

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

    /** @param string|array<mixed> $color A CSS color, or a Filament palette (Color::Amber). */
    public function backgroundColor(string | array $color): self
    {
        $this->backgroundColor = Normalize::color($color);

        return $this;
    }

    /** @param string|array<mixed> $color A CSS color, or a Filament palette (Color::Amber). */
    public function borderColor(string | array $color): self
    {
        $this->borderColor = Normalize::color($color);

        return $this;
    }

    public function borderWidth(int | float $width): self
    {
        $this->borderWidth = $width;

        return $this;
    }

    /** @param string|array<mixed> $color A CSS color, or a Filament palette (Color::Amber). */
    public function shadowColor(string | array $color): self
    {
        $this->shadowColor = Normalize::color($color);

        return $this;
    }

    public function shadowBlur(int | float $blur): self
    {
        $this->shadowBlur = $blur;

        return $this;
    }

    public function shadowOffsetX(int | float $offset): self
    {
        $this->shadowOffsetX = $offset;

        return $this;
    }

    public function shadowOffsetY(int | float $offset): self
    {
        $this->shadowOffsetY = $offset;

        return $this;
    }

    public function zlevel(int $zlevel): self
    {
        $this->zlevel = $zlevel;

        return $this;
    }

    public function z(int $z): self
    {
        $this->z = $z;

        return $this;
    }

    public function toArray(): array
    {
        $grid = [];
        if ($this->show !== null) {
            $grid['show'] = $this->show;
        }
        if ($this->containLabel !== null) {
            $grid['containLabel'] = $this->containLabel;
        }
        if ($this->width !== null) {
            $grid['width'] = $this->width;
        }
        if ($this->height !== null) {
            $grid['height'] = $this->height;
        }
        if ($this->backgroundColor !== null) {
            $grid['backgroundColor'] = $this->backgroundColor;
        }
        if ($this->borderColor !== null) {
            $grid['borderColor'] = $this->borderColor;
        }
        if ($this->borderWidth !== null) {
            $grid['borderWidth'] = $this->borderWidth;
        }
        if ($this->shadowColor !== null) {
            $grid['shadowColor'] = $this->shadowColor;
        }
        if ($this->shadowBlur !== null) {
            $grid['shadowBlur'] = $this->shadowBlur;
        }
        if ($this->shadowOffsetX !== null) {
            $grid['shadowOffsetX'] = $this->shadowOffsetX;
        }
        if ($this->shadowOffsetY !== null) {
            $grid['shadowOffsetY'] = $this->shadowOffsetY;
        }
        if ($this->zlevel !== null) {
            $grid['zlevel'] = $this->zlevel;
        }
        if ($this->z !== null) {
            $grid['z'] = $this->z;
        }

        return $this->mergeRaw(array_merge($grid, $this->boxLayoutArray()));
    }
}
