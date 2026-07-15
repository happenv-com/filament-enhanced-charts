<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Illuminate\Support\Traits\Conditionable;

/**
 * The brush component — lets the user select a region of the chart (rectangle,
 * polygon, …) to highlight or drive linked interactions. Pair with the
 * `Toolbox\Brush` feature button that turns brushing on.
 */
final class Brush implements Node
{
    use Conditionable;
    use HasRaw;

    /** @var array<string, mixed> */
    private array $properties = [];

    public static function make(): self
    {
        return new self;
    }

    /** @param list<string> $toolbox Enabled brush tools, e.g. `['rect', 'polygon', 'lineX', 'keep', 'clear']`. */
    public function toolbox(array $toolbox): self
    {
        $this->properties['toolbox'] = $toolbox;

        return $this;
    }

    public function brushType(string $brushType): self
    {
        $this->properties['brushType'] = $brushType;

        return $this;
    }

    /** Single or multiple concurrent selections. */
    public function brushMode(string $brushMode): self
    {
        $this->properties['brushMode'] = $brushMode;

        return $this;
    }

    /** @param int|list<int>|string $brushLink Which series' data-index selections stay linked ('all' or a list). */
    public function brushLink(int | array | string $brushLink): self
    {
        $this->properties['brushLink'] = $brushLink;

        return $this;
    }

    /** @param int|list<int>|string $seriesIndex */
    public function seriesIndex(int | array | string $seriesIndex): self
    {
        $this->properties['seriesIndex'] = $seriesIndex;

        return $this;
    }

    /** @param int|list<int>|string $xAxisIndex */
    public function xAxisIndex(int | array | string $xAxisIndex): self
    {
        $this->properties['xAxisIndex'] = $xAxisIndex;

        return $this;
    }

    /** @param int|list<int>|string $yAxisIndex */
    public function yAxisIndex(int | array | string $yAxisIndex): self
    {
        $this->properties['yAxisIndex'] = $yAxisIndex;

        return $this;
    }

    /** @param int|list<int>|string $geoIndex */
    public function geoIndex(int | array | string $geoIndex): self
    {
        $this->properties['geoIndex'] = $geoIndex;

        return $this;
    }

    public function transformable(bool $transformable = true): self
    {
        $this->properties['transformable'] = $transformable;

        return $this;
    }

    public function removeOnClick(bool $removeOnClick = true): self
    {
        $this->properties['removeOnClick'] = $removeOnClick;

        return $this;
    }

    public function throttleType(string $throttleType): self
    {
        $this->properties['throttleType'] = $throttleType;

        return $this;
    }

    public function throttleDelay(int $throttleDelay): self
    {
        $this->properties['throttleDelay'] = $throttleDelay;

        return $this;
    }

    /** @param array<string, mixed> $brushStyle */
    public function brushStyle(array $brushStyle): self
    {
        $this->properties['brushStyle'] = $brushStyle;

        return $this;
    }

    /** @param array<string, mixed> $inBrush Visual encoding applied to selected data. */
    public function inBrush(array $inBrush): self
    {
        $this->properties['inBrush'] = $inBrush;

        return $this;
    }

    /** @param array<string, mixed> $outOfBrush Visual encoding applied to unselected data. */
    public function outOfBrush(array $outOfBrush): self
    {
        $this->properties['outOfBrush'] = $outOfBrush;

        return $this;
    }

    public function z(int $z): self
    {
        $this->properties['z'] = $z;

        return $this;
    }

    public function toArray(): array
    {
        return $this->mergeRaw($this->properties);
    }
}
