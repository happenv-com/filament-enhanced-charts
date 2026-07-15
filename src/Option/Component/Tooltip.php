<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component;

use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Enums\TooltipTrigger;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Illuminate\Support\Traits\Conditionable;

final class Tooltip implements Node
{
    use Conditionable;
    use HasRaw;

    private ?bool $show = null;

    private ?string $trigger = null;

    /** @var string|array{__js__: string}|null */
    private string | array | null $formatter = null;

    /** @var array{__js__: string}|null */
    private ?array $valueFormatter = null;

    private ?bool $confine = null;

    /** @var array<string, mixed>|null */
    private ?array $axisPointer = null;

    /** @var string|array<mixed>|null */
    private string | array | null $backgroundColor = null;

    /** @var string|array<mixed>|null */
    private string | array | null $borderColor = null;

    private int | float | null $borderWidth = null;

    /** @var int|array<int, int>|null */
    private int | array | null $padding = null;

    /** @var string|array<mixed>|RawJs|null */
    private string | array | RawJs | null $position = null;

    private ?string $triggerOn = null;

    /** @var array<string, mixed>|null */
    private ?array $textStyle = null;

    private ?string $appendTo = null;

    private ?bool $enterable = null;

    private ?string $extraCssText = null;

    private ?string $order = null;

    private ?bool $showContent = null;

    private int | float | null $transitionDuration = null;

    /** @var int|array<int, int>|null */
    private int | array | null $borderRadius = null;

    public static function make(): self
    {
        return new self;
    }

    public function show(bool $show = true): self
    {
        $this->show = $show;

        return $this;
    }

    public function trigger(TooltipTrigger | string $trigger): self
    {
        $this->trigger = Normalize::enum($trigger);

        return $this;
    }

    /**
     * The axis pointer shown alongside the tooltip on `trigger('axis')`:
     * `type` ('line'|'shadow'|'cross'|'none'), `label`, `lineStyle`,
     * `shadowStyle`, `crossStyle`, etc.
     *
     * @param  array<string, mixed>  $config
     */
    public function axisPointer(array $config): self
    {
        $this->axisPointer = $config;

        return $this;
    }

    /**
     * A bare string is a literal ECharts template ('{b}: {c}') and passes
     * through unchanged; a RawJs is executable JS emitted as a `{__js__}`
     * marker for the client reviver.
     */
    public function formatter(RawJs | string $formatter): self
    {
        $this->formatter = Normalize::formatter($formatter);

        return $this;
    }

    public function valueFormatter(RawJs | string $formatter): self
    {
        $this->valueFormatter = Normalize::js($formatter);

        return $this;
    }

    public function confine(bool $confine = true): self
    {
        $this->confine = $confine;

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

    /** @param int|array<int, int> $padding A single value, or [top, right, bottom, left]. */
    public function padding(int | array $padding): self
    {
        $this->padding = $padding;

        return $this;
    }

    /**
     * Absolute `[x, y]` pixel/percent pair, a built-in keyword
     * ('inside'/'top'/'left'/'right'/'bottom', `trigger('item')` only), a
     * box layout (`['top' => 10, 'left' => '10%']`), or a RawJs positioning
     * callback `(point, params, dom, rect, size) => [x, y]`.
     *
     * @param  string|array<mixed>|RawJs  $position
     */
    public function position(string | array | RawJs $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function triggerOn(string $triggerOn): self
    {
        $this->triggerOn = $triggerOn;

        return $this;
    }

    /** @param Label|array<string, mixed> $style */
    public function textStyle(Label | array $style): self
    {
        $this->textStyle = Normalize::arr($style);

        return $this;
    }

    /** A CSS selector string, or `'body'`, resolving the tooltip's DOM container. */
    public function appendTo(string $appendTo): self
    {
        $this->appendTo = $appendTo;

        return $this;
    }

    public function enterable(bool $enterable = true): self
    {
        $this->enterable = $enterable;

        return $this;
    }

    public function extraCssText(string $extraCssText): self
    {
        $this->extraCssText = $extraCssText;

        return $this;
    }

    /** Sort order for a multi-series `trigger('axis')` tooltip: 'seriesAsc'|'seriesDesc'|'valueAsc'|'valueDesc'. */
    public function order(string $order): self
    {
        $this->order = $order;

        return $this;
    }

    /** Whether to show the tooltip floating layer itself, while still tracking/firing its events. */
    public function showContent(bool $showContent = true): self
    {
        $this->showContent = $showContent;

        return $this;
    }

    /** Transition duration (in seconds) of the tooltip's position when it follows the pointer. */
    public function transitionDuration(int | float $transitionDuration): self
    {
        $this->transitionDuration = $transitionDuration;

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
        $tooltip = [];
        if ($this->show !== null) {
            $tooltip['show'] = $this->show;
        }
        if ($this->trigger !== null) {
            $tooltip['trigger'] = $this->trigger;
        }
        if ($this->triggerOn !== null) {
            $tooltip['triggerOn'] = $this->triggerOn;
        }
        if ($this->axisPointer !== null) {
            $tooltip['axisPointer'] = Normalize::value($this->axisPointer);
        }
        if ($this->formatter !== null) {
            $tooltip['formatter'] = $this->formatter;
        }
        if ($this->valueFormatter !== null) {
            $tooltip['valueFormatter'] = $this->valueFormatter;
        }
        if ($this->position !== null) {
            $tooltip['position'] = Normalize::value($this->position);
        }
        if ($this->backgroundColor !== null) {
            $tooltip['backgroundColor'] = $this->backgroundColor;
        }
        if ($this->borderColor !== null) {
            $tooltip['borderColor'] = $this->borderColor;
        }
        if ($this->borderWidth !== null) {
            $tooltip['borderWidth'] = $this->borderWidth;
        }
        if ($this->padding !== null) {
            $tooltip['padding'] = $this->padding;
        }
        if ($this->confine !== null) {
            $tooltip['confine'] = $this->confine;
        }
        if ($this->textStyle !== null) {
            $tooltip['textStyle'] = Normalize::value($this->textStyle);
        }
        if ($this->appendTo !== null) {
            $tooltip['appendTo'] = $this->appendTo;
        }
        if ($this->enterable !== null) {
            $tooltip['enterable'] = $this->enterable;
        }
        if ($this->extraCssText !== null) {
            $tooltip['extraCssText'] = $this->extraCssText;
        }
        if ($this->order !== null) {
            $tooltip['order'] = $this->order;
        }
        if ($this->showContent !== null) {
            $tooltip['showContent'] = $this->showContent;
        }
        if ($this->transitionDuration !== null) {
            $tooltip['transitionDuration'] = $this->transitionDuration;
        }
        if ($this->borderRadius !== null) {
            $tooltip['borderRadius'] = $this->borderRadius;
        }

        return $this->mergeRaw($tooltip);
    }
}
