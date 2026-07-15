<?php

namespace Happenv\FilamentEnhancedCharts\Option\Style;

use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Illuminate\Support\Traits\Conditionable;

/**
 * Text label styling (series labels, axis-name labels, …): visibility,
 * placement, formatter, and font. A text style, not a shape style, so it does
 * not share the Style base.
 */
final class Label implements Node
{
    use Conditionable;
    use HasRaw;

    /** @var array<string, mixed> */
    private array $properties = [];

    public static function make(): self
    {
        return new self;
    }

    public function show(bool $show = true): self
    {
        $this->properties['show'] = $show;

        return $this;
    }

    public function position(string $position): self
    {
        $this->properties['position'] = $position;

        return $this;
    }

    public function formatter(RawJs | string $formatter): self
    {
        $this->properties['formatter'] = Normalize::formatter($formatter);

        return $this;
    }

    /** @param string|array<mixed> $color A CSS color, or a Filament palette (Color::Amber). */
    public function color(string | array $color): self
    {
        $this->properties['color'] = Normalize::color($color);

        return $this;
    }

    public function fontSize(int $size): self
    {
        $this->properties['fontSize'] = $size;

        return $this;
    }

    public function fontWeight(string | int $weight): self
    {
        $this->properties['fontWeight'] = $weight;

        return $this;
    }

    public function bold(): self
    {
        return $this->fontWeight('bold');
    }

    public function fontFamily(string $family): self
    {
        $this->properties['fontFamily'] = $family;

        return $this;
    }

    /**
     * Label rotation: degrees, or — for sunburst labels — the keywords
     * `'radial'` / `'tangential'`.
     */
    public function rotate(int | float | string $rotate): self
    {
        $this->properties['rotate'] = $rotate;

        return $this;
    }

    /**
     * Distance to the host element, or — where ECharts documents it — an
     * `[horizontal, vertical]` pair.
     *
     * @param  int|float|array<int, int|float>  $distance
     */
    public function distance(int | float | array $distance): self
    {
        $this->properties['distance'] = $distance;

        return $this;
    }

    /** Pie labels: horizontal alignment strategy — `'none'`, `'labelLine'` or `'edge'`. */
    public function alignTo(string $alignTo): self
    {
        $this->properties['alignTo'] = $alignTo;

        return $this;
    }

    /** Pie labels with `alignTo('edge')`: distance between the label and the chart edge. */
    public function edgeDistance(int | string $edgeDistance): self
    {
        $this->properties['edgeDistance'] = $edgeDistance;

        return $this;
    }

    /** Pie labels with `alignTo('none')`: margin kept from the viewport edge. */
    public function bleedMargin(int | float $bleedMargin): self
    {
        $this->properties['bleedMargin'] = $bleedMargin;

        return $this;
    }

    /** Pie labels: distance between the label and the end of its leader line. */
    public function distanceToLabelLine(int | float $distanceToLabelLine): self
    {
        $this->properties['distanceToLabelLine'] = $distanceToLabelLine;

        return $this;
    }

    public function align(string $align): self
    {
        $this->properties['align'] = $align;

        return $this;
    }

    public function verticalAlign(string $verticalAlign): self
    {
        $this->properties['verticalAlign'] = $verticalAlign;

        return $this;
    }

    /**
     * Named style fragments referenced from a `formatter` template via
     * `{styleName|text}`, e.g. `['name' => ['color' => '#fff', 'fontSize' => 14]]`.
     *
     * @param  array<string, array<string, mixed>>  $rich
     */
    public function rich(array $rich): self
    {
        $this->properties['rich'] = $rich;

        return $this;
    }

    /** @param string|array<mixed> $color A CSS color, or a Filament palette (Color::Amber). */
    public function backgroundColor(string | array $color): self
    {
        $this->properties['backgroundColor'] = Normalize::color($color);

        return $this;
    }

    /** @param string|array<mixed> $color A CSS color, or a Filament palette (Color::Amber). */
    public function borderColor(string | array $color): self
    {
        $this->properties['borderColor'] = Normalize::color($color);

        return $this;
    }

    public function borderWidth(int | float $width): self
    {
        $this->properties['borderWidth'] = $width;

        return $this;
    }

    /** @param int|array<int, int> $radius A single radius or [tl, tr, br, bl]. */
    public function borderRadius(int | array $radius): self
    {
        $this->properties['borderRadius'] = $radius;

        return $this;
    }

    public function borderType(string $type): self
    {
        $this->properties['borderType'] = $type;

        return $this;
    }

    /** @param int|array<int, int> $padding A single value, or [top, right, bottom, left] (ECharts padding shorthand). */
    public function padding(int | array $padding): self
    {
        $this->properties['padding'] = $padding;

        return $this;
    }

    public function width(int $width): self
    {
        $this->properties['width'] = $width;

        return $this;
    }

    public function height(int $height): self
    {
        $this->properties['height'] = $height;

        return $this;
    }

    public function lineHeight(int | float $lineHeight): self
    {
        $this->properties['lineHeight'] = $lineHeight;

        return $this;
    }

    /** @param array{int|float, int|float} $offset [x, y] pixel offset from the label's anchor point. */
    public function offset(array $offset): self
    {
        $this->properties['offset'] = $offset;

        return $this;
    }

    /** @param string|array<mixed> $color A CSS color, or a Filament palette (Color::Amber). */
    public function textBorderColor(string | array $color): self
    {
        $this->properties['textBorderColor'] = Normalize::color($color);

        return $this;
    }

    public function textBorderWidth(int | float $width): self
    {
        $this->properties['textBorderWidth'] = $width;

        return $this;
    }

    /** @param string $overflow One of ECharts' 'truncate', 'break', 'breakAll', 'none'. */
    public function overflow(string $overflow): self
    {
        $this->properties['overflow'] = $overflow;

        return $this;
    }

    public function minMargin(int | float $minMargin): self
    {
        $this->properties['minMargin'] = $minMargin;

        return $this;
    }

    public function shadowBlur(int | float $blur): self
    {
        $this->properties['shadowBlur'] = $blur;

        return $this;
    }

    /** @param string|array<mixed> $color A CSS color, or a Filament palette (Color::Amber). */
    public function shadowColor(string | array $color): self
    {
        $this->properties['shadowColor'] = Normalize::color($color);

        return $this;
    }

    /** Disable mouse/touch events on the label so it doesn't intercept hover/click. */
    public function silent(bool $silent = true): self
    {
        $this->properties['silent'] = $silent;

        return $this;
    }

    public function toArray(): array
    {
        return $this->mergeRaw($this->properties);
    }
}
