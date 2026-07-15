<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component;

use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Enums\Orient;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLayout;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;
use Happenv\FilamentEnhancedCharts\Option\Style\LineStyle;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Illuminate\Support\Traits\Conditionable;

final class Legend implements Node
{
    use Conditionable;
    use HasLayout;
    use HasRaw;

    private ?bool $show = null;

    private ?string $type = null;

    private ?string $orient = null;

    /** @var list<mixed>|null */
    private ?array $data = null;

    /** @var array<string, mixed>|null */
    private ?array $textStyle = null;

    private bool | string | null $selectedMode = null;

    private ?int $itemGap = null;

    private ?string $align = null;

    /** @var int|array<int, int>|null */
    private int | array | null $padding = null;

    private ?int $itemWidth = null;

    private ?int $itemHeight = null;

    private ?string $icon = null;

    /** @var string|array{__js__: string}|null */
    private string | array | null $formatter = null;

    /** @var string|array<mixed>|null */
    private string | array | null $inactiveColor = null;

    /** @var array<string, mixed>|null */
    private ?array $itemStyle = null;

    /** @var array<string, mixed>|null */
    private ?array $lineStyle = null;

    private int | string | null $width = null;

    private int | string | null $height = null;

    /** @var array<string, bool>|null */
    private ?array $selected = null;

    /** @var string|array<mixed>|null */
    private string | array | null $borderColor = null;

    /** @var string|array<mixed>|null */
    private string | array | null $backgroundColor = null;

    private int | float | null $borderWidth = null;

    /** @var int|array<int, int>|null */
    private int | array | null $borderRadius = null;

    public static function make(): self
    {
        return new self;
    }

    /**
     * Explicit legend items (names, or `['name' => ..., 'icon' => ...]` entries).
     *
     * @param  list<mixed>  $data
     */
    public function data(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /** @param Label|array<string, mixed> $textStyle */
    public function textStyle(Label | array $textStyle): self
    {
        $this->textStyle = Normalize::arr($textStyle);

        return $this;
    }

    public function selectedMode(bool | string $selectedMode): self
    {
        $this->selectedMode = $selectedMode;

        return $this;
    }

    public function itemGap(int $itemGap): self
    {
        $this->itemGap = $itemGap;

        return $this;
    }

    public function show(bool $show = true): self
    {
        $this->show = $show;

        return $this;
    }

    public function orient(Orient | string $orient): self
    {
        $this->orient = Normalize::enum($orient);

        return $this;
    }

    /** The legend layout style: `'plain'` (default) or `'scroll'` (paginated when it overflows). */
    public function type(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /** Legend text alignment relative to its icon: `'auto'`, `'left'`, or `'right'`. */
    public function align(string $align): self
    {
        $this->align = $align;

        return $this;
    }

    /** @param int|array<int, int> $padding A single value, or [top, right, bottom, left]. */
    public function padding(int | array $padding): self
    {
        $this->padding = $padding;

        return $this;
    }

    public function itemWidth(int $itemWidth): self
    {
        $this->itemWidth = $itemWidth;

        return $this;
    }

    public function itemHeight(int $itemHeight): self
    {
        $this->itemHeight = $itemHeight;

        return $this;
    }

    /** Legend icon shape (e.g. `'circle'`, `'rect'`, `'roundRect'`) or a `path://`/`image://` string. */
    public function icon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * A bare string is a literal ECharts template ('{name}') and passes through
     * unchanged; a RawJs is executable JS emitted as a `{__js__}` marker for the
     * client reviver.
     */
    public function formatter(RawJs | string $formatter): self
    {
        $this->formatter = Normalize::formatter($formatter);

        return $this;
    }

    /** @param string|array<mixed> $color A CSS color, or a Filament palette (Color::Amber). */
    public function inactiveColor(string | array $color): self
    {
        $this->inactiveColor = Normalize::color($color);

        return $this;
    }

    /** @param ItemStyle|array<string, mixed> $itemStyle */
    public function itemStyle(ItemStyle | array $itemStyle): self
    {
        $this->itemStyle = Normalize::arr($itemStyle);

        return $this;
    }

    /** @param LineStyle|array<string, mixed> $lineStyle */
    public function lineStyle(LineStyle | array $lineStyle): self
    {
        $this->lineStyle = Normalize::arr($lineStyle);

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
     * The initial visibility of each legend item, keyed by series/data name.
     *
     * @param  array<string, bool>  $selected
     */
    public function selected(array $selected): self
    {
        $this->selected = $selected;

        return $this;
    }

    /** @param string|array<mixed> $color A CSS color, or a Filament palette (Color::Amber). */
    public function borderColor(string | array $color): self
    {
        $this->borderColor = Normalize::color($color);

        return $this;
    }

    /** @param string|array<mixed> $color A CSS color, or a Filament palette (Color::Amber). */
    public function backgroundColor(string | array $color): self
    {
        $this->backgroundColor = Normalize::color($color);

        return $this;
    }

    public function borderWidth(int | float $borderWidth): self
    {
        $this->borderWidth = $borderWidth;

        return $this;
    }

    /** @param int|array<int, int> $borderRadius A single radius or [tl, tr, br, bl]. */
    public function borderRadius(int | array $borderRadius): self
    {
        $this->borderRadius = $borderRadius;

        return $this;
    }

    public function toArray(): array
    {
        $legend = [];
        if ($this->show !== null) {
            $legend['show'] = $this->show;
        }
        if ($this->type !== null) {
            $legend['type'] = $this->type;
        }
        if ($this->orient !== null) {
            $legend['orient'] = $this->orient;
        }
        if ($this->data !== null) {
            $legend['data'] = $this->data;
        }
        if ($this->textStyle !== null) {
            $legend['textStyle'] = $this->textStyle;
        }
        if ($this->selectedMode !== null) {
            $legend['selectedMode'] = $this->selectedMode;
        }
        if ($this->itemGap !== null) {
            $legend['itemGap'] = $this->itemGap;
        }
        if ($this->align !== null) {
            $legend['align'] = $this->align;
        }
        if ($this->padding !== null) {
            $legend['padding'] = $this->padding;
        }
        if ($this->itemWidth !== null) {
            $legend['itemWidth'] = $this->itemWidth;
        }
        if ($this->itemHeight !== null) {
            $legend['itemHeight'] = $this->itemHeight;
        }
        if ($this->icon !== null) {
            $legend['icon'] = $this->icon;
        }
        if ($this->formatter !== null) {
            $legend['formatter'] = $this->formatter;
        }
        if ($this->inactiveColor !== null) {
            $legend['inactiveColor'] = $this->inactiveColor;
        }
        if ($this->itemStyle !== null) {
            $legend['itemStyle'] = Normalize::value($this->itemStyle);
        }
        if ($this->lineStyle !== null) {
            $legend['lineStyle'] = Normalize::value($this->lineStyle);
        }
        if ($this->width !== null) {
            $legend['width'] = $this->width;
        }
        if ($this->height !== null) {
            $legend['height'] = $this->height;
        }
        if ($this->selected !== null) {
            $legend['selected'] = $this->selected;
        }
        if ($this->borderColor !== null) {
            $legend['borderColor'] = $this->borderColor;
        }
        if ($this->backgroundColor !== null) {
            $legend['backgroundColor'] = $this->backgroundColor;
        }
        if ($this->borderWidth !== null) {
            $legend['borderWidth'] = $this->borderWidth;
        }
        if ($this->borderRadius !== null) {
            $legend['borderRadius'] = $this->borderRadius;
        }

        return $this->mergeRaw(array_merge($legend, $this->boxLayoutArray()));
    }
}
