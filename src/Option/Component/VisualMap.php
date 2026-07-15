<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component;

use BcMath\Number;
use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Enums\Orient;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLayout;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Illuminate\Support\Traits\Conditionable;

final class VisualMap implements Node
{
    use Conditionable;
    use HasLayout;
    use HasRaw;

    private int | float | string | Number | null $min = null;

    private int | float | string | Number | null $max = null;

    /** @var list<array<string, mixed>>|null */
    private ?array $pieces = null;

    /** @var list<mixed>|null */
    private ?array $categories = null;

    private int | string | null $dimension = null;

    /** @var int|list<int>|null */
    private int | array | null $seriesIndex = null;

    private int | float | null $precision = null;

    /** @var list<string>|null */
    private ?array $text = null;

    /** @var array<string, mixed>|null */
    private ?array $textStyle = null;

    private ?bool $hoverLink = null;

    private ?int $itemWidth = null;

    private ?int $itemHeight = null;

    /** @var list<int|float>|null */
    private ?array $range = null;

    private ?int $splitNumber = null;

    /** @var string|array{__js__: string}|null */
    private string | array | null $formatter = null;

    private ?bool $calculable = null;

    private ?bool $show = null;

    private ?string $orient = null;

    /** @var array<string, mixed>|null */
    private ?array $inRange = null;

    /** @var array<string, mixed>|null */
    private ?array $outOfRange = null;

    private ?bool $realtime = null;

    /** @var array<string, mixed>|null */
    private ?array $controller = null;

    private int | float | null $textGap = null;

    /** @var string|array<mixed>|null */
    private string | array | null $borderColor = null;

    /** @var string|array<mixed>|null */
    private string | array | null $backgroundColor = null;

    private int | float | null $borderWidth = null;

    private function __construct(private readonly string $type) {}

    public static function continuous(): self
    {
        return new self('continuous');
    }

    public static function piecewise(): self
    {
        return new self('piecewise');
    }

    public function min(int | float | string | Number $min): self
    {
        $this->min = $min;

        return $this;
    }

    public function max(int | float | string | Number $max): self
    {
        $this->max = $max;

        return $this;
    }

    /** @param list<array<string, mixed>> $pieces */
    public function pieces(array $pieces): self
    {
        $this->pieces = $pieces;

        return $this;
    }

    /** Category names for `pieces`-less categorical mapping (bound to `dimension`'s data). */
    public function categories(array $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    /** The data dimension the visual map reads: a column index, or a named dimension. */
    public function dimension(int | string $dimension): self
    {
        $this->dimension = $dimension;

        return $this;
    }

    /** The series index(es) this visual map controls, when it isn't all series. */
    public function seriesIndex(int | array $seriesIndex): self
    {
        $this->seriesIndex = $seriesIndex;

        return $this;
    }

    /** Decimal precision for the min/max labels of a continuous visual map (e.g. `0.1`). */
    public function precision(int | float $precision): self
    {
        $this->precision = $precision;

        return $this;
    }

    /**
     * Text shown at the two ends of the visual map (e.g. `['High', 'Low']`),
     * paired with `min`/`max`.
     *
     * @param  list<string>  $text
     */
    public function text(array $text): self
    {
        $this->text = $text;

        return $this;
    }

    /** @param Label|array<string, mixed> $textStyle */
    public function textStyle(Label | array $textStyle): self
    {
        $this->textStyle = Normalize::arr($textStyle);

        return $this;
    }

    public function hoverLink(bool $hoverLink = true): self
    {
        $this->hoverLink = $hoverLink;

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

    /** The currently selected `[min, max]` sub-range within `min()`/`max()`. */
    public function range(array $range): self
    {
        $this->range = $range;

        return $this;
    }

    /** Split the continuous range into this many equal pieces (auto piecewise). */
    public function splitNumber(int $splitNumber): self
    {
        $this->splitNumber = $splitNumber;

        return $this;
    }

    /**
     * A bare string is a literal ECharts template ('{value}') and passes
     * through unchanged; a RawJs is executable JS emitted as a `{__js__}`
     * marker for the client reviver.
     */
    public function formatter(RawJs | string $formatter): self
    {
        $this->formatter = Normalize::formatter($formatter);

        return $this;
    }

    public function calculable(bool $calculable = true): self
    {
        $this->calculable = $calculable;

        return $this;
    }

    /** Hide the visualMap control while still driving the color/size mapping. */
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

    /** @param array<string, mixed> $inRange e.g. `['color' => ['#e0ffff', '#006edd']]` */
    public function inRange(array $inRange): self
    {
        $this->inRange = $inRange;

        return $this;
    }

    /** @param array<string, mixed> $outOfRange */
    public function outOfRange(array $outOfRange): self
    {
        $this->outOfRange = $outOfRange;

        return $this;
    }

    /** The in-range color scale — sugar for `inRange(['color' => [...]])`. */
    public function colors(string ...$colors): self
    {
        $this->inRange = array_merge($this->inRange ?? [], ['color' => array_values($colors)]);

        return $this;
    }

    /** Continuously update the chart while dragging the visual map handle, instead of on drop. */
    public function realtime(bool $realtime = true): self
    {
        $this->realtime = $realtime;

        return $this;
    }

    /** Style of the visual map's own controller handle/indicator, e.g. `['inRange' => [...]]`. */
    public function controller(array $controller): self
    {
        $this->controller = $controller;

        return $this;
    }

    /** Gap between the visual map's text labels and its color bar/handles. */
    public function textGap(int | float $textGap): self
    {
        $this->textGap = $textGap;

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

    public function toArray(): array
    {
        $map = ['type' => $this->type];
        if ($this->min !== null) {
            $map['min'] = Normalize::value($this->min);
        }
        if ($this->max !== null) {
            $map['max'] = Normalize::value($this->max);
        }
        if ($this->pieces !== null) {
            $map['pieces'] = Normalize::value($this->pieces);
        }
        if ($this->categories !== null) {
            $map['categories'] = $this->categories;
        }
        if ($this->dimension !== null) {
            $map['dimension'] = $this->dimension;
        }
        if ($this->seriesIndex !== null) {
            $map['seriesIndex'] = $this->seriesIndex;
        }
        if ($this->precision !== null) {
            $map['precision'] = $this->precision;
        }
        if ($this->text !== null) {
            $map['text'] = $this->text;
        }
        if ($this->textStyle !== null) {
            $map['textStyle'] = Normalize::value($this->textStyle);
        }
        if ($this->hoverLink !== null) {
            $map['hoverLink'] = $this->hoverLink;
        }
        if ($this->itemWidth !== null) {
            $map['itemWidth'] = $this->itemWidth;
        }
        if ($this->itemHeight !== null) {
            $map['itemHeight'] = $this->itemHeight;
        }
        if ($this->range !== null) {
            $map['range'] = $this->range;
        }
        if ($this->splitNumber !== null) {
            $map['splitNumber'] = $this->splitNumber;
        }
        if ($this->formatter !== null) {
            $map['formatter'] = $this->formatter;
        }
        if ($this->calculable !== null) {
            $map['calculable'] = $this->calculable;
        }
        if ($this->show !== null) {
            $map['show'] = $this->show;
        }
        if ($this->orient !== null) {
            $map['orient'] = $this->orient;
        }
        if ($this->inRange !== null) {
            $map['inRange'] = Normalize::value($this->inRange);
        }
        if ($this->outOfRange !== null) {
            $map['outOfRange'] = Normalize::value($this->outOfRange);
        }
        $map = array_merge($map, $this->boxLayoutArray());
        if ($this->realtime !== null) {
            $map['realtime'] = $this->realtime;
        }
        if ($this->controller !== null) {
            $map['controller'] = Normalize::value($this->controller);
        }
        if ($this->textGap !== null) {
            $map['textGap'] = $this->textGap;
        }
        if ($this->borderColor !== null) {
            $map['borderColor'] = $this->borderColor;
        }
        if ($this->backgroundColor !== null) {
            $map['backgroundColor'] = $this->backgroundColor;
        }
        if ($this->borderWidth !== null) {
            $map['borderWidth'] = $this->borderWidth;
        }

        return $this->mergeRaw($map);
    }
}
