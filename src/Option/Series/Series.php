<?php

namespace Happenv\FilamentEnhancedCharts\Option\Series;

use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Enums\CoordinateSystem;
use Happenv\FilamentEnhancedCharts\Option\Component\Tooltip;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasAnimation;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Happenv\FilamentEnhancedCharts\Option\Mark\MarkArea;
use Happenv\FilamentEnhancedCharts\Option\Mark\MarkLine;
use Happenv\FilamentEnhancedCharts\Option\Mark\MarkPoint;
use Happenv\FilamentEnhancedCharts\Option\Style\Emphasis;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Illuminate\Support\Traits\Conditionable;

abstract class Series implements Node
{
    use Conditionable;
    use HasAnimation;
    use HasRaw;

    private ?string $name = null;

    private ?string $id = null;

    private string | array | null $color = null;

    private ?int $xAxisIndex = null;

    private ?int $yAxisIndex = null;

    private ?string $coordinateSystem = null;

    private ?int $polarIndex = null;

    private ?int $geoIndex = null;

    private ?int $calendarIndex = null;

    private ?int $singleAxisIndex = null;

    private ?int $matrixIndex = null;

    private ?int $datasetIndex = null;

    private ?string $seriesLayoutBy = null;

    /** @var array<int, mixed>|null */
    private ?array $dimensions = null;

    /** @var array<string, mixed>|null */
    private ?array $encode = null;

    /** @var array<int, mixed>|null */
    private ?array $coord = null;

    /** @var array<string, mixed>|null */
    private ?array $markLine = null;

    /** @var array<string, mixed>|null */
    private ?array $markPoint = null;

    /** @var array<string, mixed>|null */
    private ?array $markArea = null;

    /** @var array<string, mixed>|null */
    private ?array $emphasis = null;

    /** @var array<string, mixed>|null */
    private ?array $label = null;

    /** @var array<string, mixed>|null */
    private ?array $itemStyle = null;

    private ?int $z = null;

    private ?int $zlevel = null;

    private ?bool $silent = null;

    private ?bool $large = null;

    private ?int $largeThreshold = null;

    private ?bool $clip = null;

    private ?string $colorBy = null;

    private ?string $blendMode = null;

    private ?string $cursor = null;

    private ?bool $legendHoverLink = null;

    private ?string $sampling = null;

    private ?int $progressive = null;

    private ?int $progressiveThreshold = null;

    private bool | string | null $selectedMode = null;

    /** @var array<string, mixed>|null */
    private ?array $tooltip = null;

    /** @var array<string, mixed>|null */
    private ?array $labelLayout = null;

    /** @var array<string, mixed>|null */
    private ?array $labelLine = null;

    abstract protected function type(): string;

    public static function make(): static
    {
        return new static;
    }

    public function name(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /** A stable identifier for this series, referenceable from action APIs and dataset transforms. */
    public function id(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    /** @param string|array<mixed> $color A CSS color, or a Filament palette (Color::Amber). */
    public function color(string | array $color): static
    {
        $this->color = Normalize::color($color);

        return $this;
    }

    /** Binds this series to a secondary x-axis by its index in Option::xAxis(). */
    public function xAxisIndex(int $index): static
    {
        $this->xAxisIndex = $index;

        return $this;
    }

    /** Binds this series to a secondary y-axis by its index in Option::yAxis(). */
    public function yAxisIndex(int $index): static
    {
        $this->yAxisIndex = $index;

        return $this;
    }

    /**
     * Bind this series to a coordinate system other than the default cartesian:
     * 'polar', 'geo', 'calendar', 'matrix', or 'singleAxis'. Pair with the
     * matching `polarIndex()`/`geoIndex()`/`calendarIndex()`/`singleAxisIndex()`.
     */
    public function coordinateSystem(CoordinateSystem | string $coordinateSystem): static
    {
        $this->coordinateSystem = $coordinateSystem instanceof CoordinateSystem ? $coordinateSystem->value : $coordinateSystem;

        return $this;
    }

    /** Binds this series to a polar coordinate by its index in Option::polar(). */
    public function polarIndex(int $index): static
    {
        $this->polarIndex = $index;

        return $this;
    }

    /** Binds this series to a geo coordinate by its index in Option::geo(). */
    public function geoIndex(int $index): static
    {
        $this->geoIndex = $index;

        return $this;
    }

    /** Binds this series to a calendar coordinate by its index in Option::calendar(). */
    public function calendarIndex(int $index): static
    {
        $this->calendarIndex = $index;

        return $this;
    }

    /** Binds this series to a single-axis by its index in Option::singleAxis(). */
    public function singleAxisIndex(int $index): static
    {
        $this->singleAxisIndex = $index;

        return $this;
    }

    /** Binds this series to a matrix coordinate by its index in Option::matrix(). */
    public function matrixIndex(int $index): static
    {
        $this->matrixIndex = $index;

        return $this;
    }

    /** Binds this series to a dataset by its index in Option::dataset(). */
    public function datasetIndex(int $index): static
    {
        $this->datasetIndex = $index;

        return $this;
    }

    /** @param  string  $seriesLayoutBy  Whether a shared `Option::dataset()` reads by `'row'` or `'column'`. */
    public function seriesLayoutBy(string $seriesLayoutBy): static
    {
        $this->seriesLayoutBy = $seriesLayoutBy;

        return $this;
    }

    /**
     * Names the dataset's dimensions, e.g. `['date', 'sales']`, so `encode()`
     * can reference them by name instead of index.
     *
     * @param  array<int, mixed>  $dimensions
     */
    public function dimensions(array $dimensions): static
    {
        $this->dimensions = $dimensions;

        return $this;
    }

    /**
     * Map dataset dimensions to visual roles/axes, e.g. `['x' => 'date', 'y' => 'sales']`.
     *
     * @param  array<string, mixed>  $encode
     */
    public function encode(array $encode): static
    {
        $this->encode = $encode;

        return $this;
    }

    /**
     * Place this series into a matrix coordinate cell, e.g. `[0, 2]` or
     * `['Mon', '10:00']`. Pair with `coordinateSystem('matrix')`.
     *
     * @param  array<int, mixed>  $coord
     */
    public function coord(array $coord): static
    {
        $this->coord = $coord;

        return $this;
    }

    /** @param MarkLine|array<string, mixed> $markLine */
    public function markLine(MarkLine | array $markLine): static
    {
        $this->markLine = Normalize::arr($markLine);

        return $this;
    }

    /** @param MarkPoint|array<string, mixed> $markPoint */
    public function markPoint(MarkPoint | array $markPoint): static
    {
        $this->markPoint = Normalize::arr($markPoint);

        return $this;
    }

    /** @param MarkArea|array<string, mixed> $markArea */
    public function markArea(MarkArea | array $markArea): static
    {
        $this->markArea = Normalize::arr($markArea);

        return $this;
    }

    /**
     * The hover/emphasis state. Pass `false` to disable it entirely (no hover
     * highlight), or an `Emphasis` builder / array to configure it.
     *
     * @param  Emphasis|array<string, mixed>|bool  $emphasis
     */
    public function emphasis(Emphasis | array | bool $emphasis = true): static
    {
        $this->emphasis = is_bool($emphasis)
            ? ['disabled' => ! $emphasis]
            : Normalize::arr($emphasis);

        return $this;
    }

    /**
     * The series label (per-point text). Accepts a `Label` builder or an array.
     *
     * @param  Label|array<string, mixed>  $label
     */
    public function label(Label | array $label): static
    {
        $this->label = Normalize::arr($label);

        return $this;
    }

    /**
     * The per-item style (fill, border, shadow…). Accepts an `ItemStyle`
     * builder or an array. What "item" means depends on the series type —
     * bars, slices, symbols, nodes, map regions, cells.
     *
     * @param  ItemStyle|array<string, mixed>  $itemStyle
     */
    public function itemStyle(ItemStyle | array $itemStyle): static
    {
        $this->itemStyle = Normalize::arr($itemStyle);

        return $this;
    }

    /** This series' stacking order relative to others sharing the same coordinate system (2D). */
    public function z(int $z): static
    {
        $this->z = $z;

        return $this;
    }

    /** This series' stacking order relative to others across all coordinate systems (canvas layer). */
    public function zlevel(int $zlevel): static
    {
        $this->zlevel = $zlevel;

        return $this;
    }

    /** Disables all interaction (hover, click, tooltip) for this series. */
    public function silent(bool $silent = true): static
    {
        $this->silent = $silent;

        return $this;
    }

    /** Enables incremental rendering for large datasets (thousands of points). */
    public function large(bool $large = true): static
    {
        $this->large = $large;

        return $this;
    }

    /** The data count threshold above which large() rendering kicks in. */
    public function largeThreshold(int $largeThreshold): static
    {
        $this->largeThreshold = $largeThreshold;

        return $this;
    }

    /** Clips graphics that overflow the drawing area (grid/coordinate system bounds). */
    public function clip(bool $clip = true): static
    {
        $this->clip = $clip;

        return $this;
    }

    /** @param  string  $colorBy  Palette assignment granularity: `'series'` or `'data'`. */
    public function colorBy(string $colorBy): static
    {
        $this->colorBy = $colorBy;

        return $this;
    }

    /** @param  string  $blendMode  Canvas composite operation for overlapping series, e.g. `'source-over'` or `'lighter'`. */
    public function blendMode(string $blendMode): static
    {
        $this->blendMode = $blendMode;

        return $this;
    }

    /** The mouse cursor shown when hovering this series, e.g. `'pointer'`. */
    public function cursor(string $cursor): static
    {
        $this->cursor = $cursor;

        return $this;
    }

    /** Whether hovering this series' legend entry highlights it. */
    public function legendHoverLink(bool $legendHoverLink = true): static
    {
        $this->legendHoverLink = $legendHoverLink;

        return $this;
    }

    /** @param  string  $sampling  Downsampling strategy for large datasets: `'lttb'`, `'average'`, `'max'`, `'min'`, or `'sum'`. */
    public function sampling(string $sampling): static
    {
        $this->sampling = $sampling;

        return $this;
    }

    /** The number of points rendered per progressive-rendering frame. */
    public function progressive(int $progressive): static
    {
        $this->progressive = $progressive;

        return $this;
    }

    /** The data count threshold above which progressive rendering kicks in. */
    public function progressiveThreshold(int $progressiveThreshold): static
    {
        $this->progressiveThreshold = $progressiveThreshold;

        return $this;
    }

    /** @param  bool|string  $selectedMode  `false` disables selection; `true`/`'multiple'`/`'single'` enable it. */
    public function selectedMode(bool | string $selectedMode): static
    {
        $this->selectedMode = $selectedMode;

        return $this;
    }

    /**
     * A per-series tooltip override, merged over the global `Option::tooltip()`.
     *
     * @param  Tooltip|array<string, mixed>  $tooltip
     */
    public function tooltip(Tooltip | array $tooltip): static
    {
        $this->tooltip = Normalize::arr($tooltip);

        return $this;
    }

    /**
     * Post-layout label adjustments (`dx`/`dy`/`hideOverlap`/`moveOverlap`/…),
     * applied after label position/alignment are computed. Pass a `RawJs`
     * callback for per-label control, or an array for static settings.
     *
     * @param  array<string, mixed>|RawJs  $labelLayout
     */
    public function labelLayout(array | RawJs $labelLayout): static
    {
        $this->labelLayout = $labelLayout instanceof RawJs ? Normalize::value($labelLayout) : $labelLayout;

        return $this;
    }

    /**
     * The leader line connecting an outside label back to its data point:
     * `show`/`length`/`length2`/`lineStyle`. A bool toggles the leader line
     * (`false` hides it); no dedicated builder — pass the array directly.
     *
     * @param  array<string, mixed>|bool  $labelLine
     */
    public function labelLine(array | bool $labelLine = true): static
    {
        $this->labelLine = is_bool($labelLine) ? ['show' => $labelLine] : $labelLine;

        return $this;
    }

    final public function toArray(): array
    {
        return $this->mergeRaw($this->build());
    }

    /** @return array<string, mixed> */
    protected function build(): array
    {
        $series = ['type' => $this->type()] + Normalize::filled([
            'id' => $this->id,
            'name' => $this->name,
            'color' => $this->color,
            'xAxisIndex' => $this->xAxisIndex,
            'yAxisIndex' => $this->yAxisIndex,
            'coordinateSystem' => $this->coordinateSystem,
            'polarIndex' => $this->polarIndex,
            'geoIndex' => $this->geoIndex,
            'calendarIndex' => $this->calendarIndex,
            'singleAxisIndex' => $this->singleAxisIndex,
            'matrixIndex' => $this->matrixIndex,
            'datasetIndex' => $this->datasetIndex,
            'seriesLayoutBy' => $this->seriesLayoutBy,
            'dimensions' => $this->dimensions,
            'encode' => $this->encode,
            'coord' => $this->coord,
            'markLine' => $this->markLine,
            'markPoint' => $this->markPoint,
            'markArea' => $this->markArea,
            'emphasis' => $this->emphasis,
            'label' => $this->label,
            'itemStyle' => $this->itemStyle,
            'z' => $this->z,
            'zlevel' => $this->zlevel,
            'silent' => $this->silent,
            'large' => $this->large,
            'largeThreshold' => $this->largeThreshold,
            'clip' => $this->clip,
            'colorBy' => $this->colorBy,
            'blendMode' => $this->blendMode,
            'cursor' => $this->cursor,
            'legendHoverLink' => $this->legendHoverLink,
            'sampling' => $this->sampling,
            'progressive' => $this->progressive,
            'progressiveThreshold' => $this->progressiveThreshold,
            'selectedMode' => $this->selectedMode,
            'tooltip' => $this->tooltip,
            'labelLayout' => $this->labelLayout,
            'labelLine' => $this->labelLine,
        ]);

        return array_merge($series, $this->animationArray());
    }
}
