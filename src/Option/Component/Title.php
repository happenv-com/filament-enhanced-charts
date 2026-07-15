<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLayout;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Illuminate\Support\Traits\Conditionable;

/**
 * An in-chart title block (a main `text` plus optional `subtext`), positioned
 * via the shared layout edges. Distinct from the Filament widget heading — use
 * this when the chart itself needs a title (e.g. multiple titles on a matrix).
 */
final class Title implements Node
{
    use Conditionable;
    use HasLayout;
    use HasRaw;

    private ?bool $show = null;

    private ?string $text = null;

    private ?string $subtext = null;

    private ?string $link = null;

    private ?string $sublink = null;

    private ?string $target = null;

    private ?string $subtarget = null;

    private ?string $textAlign = null;

    /** @var array<string, mixed>|null */
    private ?array $textStyle = null;

    /** @var array<string, mixed>|null */
    private ?array $subtextStyle = null;

    private ?int $itemGap = null;

    /** @var int|array<int, int>|null */
    private int | array | null $padding = null;

    /** @var string|array<mixed>|null */
    private string | array | null $backgroundColor = null;

    /** @var string|array<mixed>|null */
    private string | array | null $borderColor = null;

    private int | float | null $borderWidth = null;

    /** @var int|array<int, int>|null */
    private int | array | null $borderRadius = null;

    private ?bool $triggerEvent = null;

    private ?string $textBaseline = null;

    public static function make(?string $text = null): self
    {
        $title = new self;

        if ($text !== null) {
            $title->text = $text;
        }

        return $title;
    }

    public function text(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function subtext(string $subtext): self
    {
        $this->subtext = $subtext;

        return $this;
    }

    public function link(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    /** The `subtext` hyperlink target — a click on the subtitle jumps here. */
    public function sublink(string $sublink): self
    {
        $this->sublink = $sublink;

        return $this;
    }

    /** How `link` opens: `'self'` (current tab) or `'blank'` (new tab). */
    public function target(string $target): self
    {
        $this->target = $target;

        return $this;
    }

    /** How `sublink` opens: `'self'` (current tab) or `'blank'` (new tab). */
    public function subtarget(string $subtarget): self
    {
        $this->subtarget = $subtarget;

        return $this;
    }

    public function itemGap(int $itemGap): self
    {
        $this->itemGap = $itemGap;

        return $this;
    }

    /** @param int|array<int, int> $padding A single value, or [top, right, bottom, left]. */
    public function padding(int | array $padding): self
    {
        $this->padding = $padding;

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

    public function show(bool $show = true): self
    {
        $this->show = $show;

        return $this;
    }

    /** Fire mouse events (click, mouseover, …) on the title so it can drive custom interactions. */
    public function triggerEvent(bool $triggerEvent = true): self
    {
        $this->triggerEvent = $triggerEvent;

        return $this;
    }

    public function textAlign(string $textAlign): self
    {
        $this->textAlign = $textAlign;

        return $this;
    }

    /** Vertical alignment of the title block within its own box: `'top'`, `'middle'`, or `'bottom'`. */
    public function textBaseline(string $textBaseline): self
    {
        $this->textBaseline = $textBaseline;

        return $this;
    }

    /** @param Label|array<string, mixed> $textStyle */
    public function textStyle(Label | array $textStyle): self
    {
        $this->textStyle = Normalize::value(Normalize::arr($textStyle));

        return $this;
    }

    /** @param Label|array<string, mixed> $subtextStyle */
    public function subtextStyle(Label | array $subtextStyle): self
    {
        $this->subtextStyle = Normalize::value(Normalize::arr($subtextStyle));

        return $this;
    }

    public function toArray(): array
    {
        $title = [];
        if ($this->show !== null) {
            $title['show'] = $this->show;
        }
        if ($this->text !== null) {
            $title['text'] = $this->text;
        }
        if ($this->subtext !== null) {
            $title['subtext'] = $this->subtext;
        }
        if ($this->link !== null) {
            $title['link'] = $this->link;
        }
        if ($this->sublink !== null) {
            $title['sublink'] = $this->sublink;
        }
        if ($this->target !== null) {
            $title['target'] = $this->target;
        }
        if ($this->subtarget !== null) {
            $title['subtarget'] = $this->subtarget;
        }
        if ($this->textAlign !== null) {
            $title['textAlign'] = $this->textAlign;
        }
        if ($this->textStyle !== null) {
            $title['textStyle'] = $this->textStyle;
        }
        if ($this->subtextStyle !== null) {
            $title['subtextStyle'] = $this->subtextStyle;
        }
        if ($this->itemGap !== null) {
            $title['itemGap'] = $this->itemGap;
        }
        if ($this->padding !== null) {
            $title['padding'] = $this->padding;
        }
        if ($this->backgroundColor !== null) {
            $title['backgroundColor'] = $this->backgroundColor;
        }
        if ($this->borderColor !== null) {
            $title['borderColor'] = $this->borderColor;
        }
        if ($this->borderWidth !== null) {
            $title['borderWidth'] = $this->borderWidth;
        }
        if ($this->borderRadius !== null) {
            $title['borderRadius'] = $this->borderRadius;
        }
        if ($this->triggerEvent !== null) {
            $title['triggerEvent'] = $this->triggerEvent;
        }
        if ($this->textBaseline !== null) {
            $title['textBaseline'] = $this->textBaseline;
        }

        return $this->mergeRaw(array_merge($title, $this->boxLayoutArray()));
    }
}
