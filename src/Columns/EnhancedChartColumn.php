<?php

namespace Happenv\FilamentEnhancedCharts\Columns;

use Closure;
use Filament\Support\Components\Contracts\HasEmbeddedView;
use Filament\Support\Facades\FilamentAsset;
use Filament\Tables\Columns\Column;
use Happenv\FilamentEnhancedCharts\Enums\Symbol;
use Happenv\FilamentEnhancedCharts\Option\Axis\CategoryAxis;
use Happenv\FilamentEnhancedCharts\Option\Axis\ValueAxis;
use Happenv\FilamentEnhancedCharts\Option\Component\Grid;
use Happenv\FilamentEnhancedCharts\Option\Component\Legend;
use Happenv\FilamentEnhancedCharts\Option\Component\Tooltip;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\BarSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\CandlestickSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\LineSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\PieSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Illuminate\Support\Js;

class EnhancedChartColumn extends Column implements HasEmbeddedView
{
    protected ?Closure $chartResolver = null;

    protected string $renderer = 'svg';

    protected ?int $chartWidth = null;

    protected ?int $chartHeight = null;

    protected bool $sparklineFill = false;

    protected bool $sparklineAsBars = false;

    protected bool $isPiePreset = false;

    /**
     * Build the row's chart from its record. The closure receives the current
     * record (Eloquent model, object, or array) and must return an `Option`.
     */
    public function chart(Closure $resolver): static
    {
        $this->chartResolver = $resolver;

        return $this;
    }

    public function renderer(string $renderer): static
    {
        $this->renderer = $renderer;

        return $this;
    }

    /**
     * Sets the chart width in pixels. Signature-compatible with the base
     * `Column::width()` (cell width); an int drives both the chart and the cell.
     */
    #[\Override]
    public function width(int | string | Closure | null $width): static
    {
        if (is_int($width)) {
            $this->chartWidth = $width;
        }

        return parent::width($width);
    }

    public function height(int $pixels): static
    {
        $this->chartHeight = $pixels;

        return $this;
    }

    public function getRenderer(): string
    {
        return $this->renderer;
    }

    public function getChartWidth(): int
    {
        return $this->chartWidth ?? ($this->isPiePreset ? 40 : 120);
    }

    public function getChartHeight(): int
    {
        return $this->chartHeight ?? ($this->isPiePreset ? 40 : 32);
    }

    /**
     * A line/area/bar sparkline preset. `$data` receives the record and returns
     * a flat list of numbers. Chain `->fill()` for an area or `->bars()` for a
     * bar sparkline.
     */
    public function sparkline(Closure $data): static
    {
        $this->chartResolver = function (mixed $record) use ($data): ?Option {
            $values = $data($record);

            if (blank($values)) {
                return null;
            }

            return $this->buildSparkline($this->toValues($values));
        };

        return $this;
    }

    public function fill(): static
    {
        $this->sparklineFill = true;

        return $this;
    }

    public function bars(): static
    {
        $this->sparklineAsBars = true;

        return $this;
    }

    /**
     * A candlestick preset. `$data` returns a list of `[open, close, low, high]`
     * rows.
     */
    public function candles(Closure $data): static
    {
        $this->chartResolver = function (mixed $record) use ($data): ?Option {
            $rows = $data($record);

            if (blank($rows)) {
                return null;
            }

            $rows = $this->toValues($rows);

            return static::cellOption('axis')
                ->xAxis(CategoryAxis::make()->data(range(1, count($rows)))->show(false))
                ->yAxis(ValueAxis::make()->show(false)->scale())
                ->series(CandlestickSeries::make()->data($rows));
        };

        return $this;
    }

    /**
     * A donut-pie preset. `$data` returns either `['label' => value, …]` or a
     * list of `['name' => …, 'value' => …]` points. Defaults to a 40×40 cell.
     */
    public function pie(Closure $data): static
    {
        $this->isPiePreset = true;

        $this->chartResolver = function (mixed $record) use ($data): ?Option {
            $data = $data($record);

            if (blank($data)) {
                return null;
            }

            // raw() patches the formatter INTO the baseline tooltip (a typed
            // ->tooltip() call would replace it and lose appendTo/confine).
            return static::cellOption('item')
                ->raw(['tooltip' => ['formatter' => '{b}: {c} ({d}%)']])
                ->series(
                    PieSeries::make()
                        ->radius(['40%', '70%'])
                        ->label(Label::make()->show(false))
                        ->data($this->toPiePoints($data))
                );
        };

        return $this;
    }

    /**
     * The per-cell Option baseline the presets start from: a body-appended
     * tooltip (so it escapes the overflow:hidden cell), no legend, near-zero
     * margins. Public so a custom `->chart()` can start from the same
     * baseline: `->chart(fn ($record) => EnhancedChartColumn::cellOption()->series(...))`.
     */
    public static function cellOption(string $tooltipTrigger = 'item'): Option
    {
        return Option::make()
            ->tooltip(Tooltip::make()->trigger($tooltipTrigger)->appendTo('body')->confine(false))
            ->legend(Legend::make()->show(false))
            ->grid(Grid::make()->left(1)->right(1)->top(2)->bottom(2)->containLabel(false));
    }

    /**
     * @param  array<int, mixed>  $values
     */
    protected function buildSparkline(array $values): Option
    {
        if ($this->sparklineAsBars) {
            $series = BarSeries::make()->data($values);
        } else {
            $series = LineSeries::make()->data($values)->symbol(Symbol::None);

            if ($this->sparklineFill) {
                $series->area();
            }
        }

        return static::cellOption('axis')
            ->xAxis(CategoryAxis::make()->data(range(1, count($values)))->show(false)->boundaryGap($this->sparklineAsBars))
            ->yAxis(ValueAxis::make()->show(false))
            ->series($series);
    }

    /**
     * @return array<int, mixed>
     */
    protected function toValues(mixed $data): array
    {
        return is_iterable($data) ? Normalize::list($data) : [];
    }

    /**
     * Normalize pie data: `['A' => 3]` → `[['name' => 'A', 'value' => 3]]`;
     * `[['name' => …, 'value' => …]]` passes through; a bare value list becomes
     * unnamed slices.
     *
     * @return list<array<array-key, mixed>>
     */
    protected function toPiePoints(mixed $data): array
    {
        $points = [];

        if (! is_iterable($data)) {
            return $points;
        }

        foreach ($data as $key => $value) {
            if (is_array($value) && array_key_exists('value', $value)) {
                $points[] = $value;
            } elseif (is_string($key)) {
                $points[] = ['name' => $key, 'value' => $value];
            } else {
                $points[] = ['value' => $value];
            }
        }

        return $points;
    }

    /**
     * Resolve the row's Option, or null when there is no resolver / the data is
     * blank (the cell then renders the placeholder).
     */
    public function resolveOption(mixed $record): ?Option
    {
        if (! $this->chartResolver instanceof Closure) {
            return null;
        }

        // Called with the record as the first argument, so any parameter name
        // works (`fn ($record)`, `fn ($r)`, `fn (Order $order)`, or `fn ()`).
        $option = ($this->chartResolver)($record);

        return $option instanceof Option ? $option : null;
    }

    public function toEmbeddedHtml(): string
    {
        $option = $this->resolveOption($this->getRecord());

        if (! $option instanceof Option) {
            $placeholder = $this->getPlaceholder();

            return filled($placeholder)
                ? '<div class="fi-ta-placeholder">' . e($placeholder) . '</div>'
                : '<div></div>';
        }

        $src = FilamentAsset::getAlpineComponentSrc('filament-enhanced-charts-column', 'happenv/filament-enhanced-charts');
        $options = Js::from($option->toArray());
        // The record key is user data — escape it so a quote in a string PK
        // can't break out of the attribute.
        $key = e('fech-' . $this->getName() . '-' . $this->getRecordKey());
        $width = $this->getChartWidth();
        $height = $this->getChartHeight();
        $renderer = e($this->getRenderer());

        return <<<HTML
            <div wire:key="{$key}" x-load x-load-src="{$src}"
                 x-data="echartsColumn({ options: {$options}, renderer: '{$renderer}', width: {$width}, height: {$height} })">
                <div wire:ignore x-ref="c" style="width: {$width}px; height: {$height}px;"></div>
            </div>
            HTML;
    }
}
