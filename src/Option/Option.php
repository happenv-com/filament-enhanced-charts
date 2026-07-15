<?php

namespace Happenv\FilamentEnhancedCharts\Option;

use Happenv\FilamentEnhancedCharts\Option\Axis\Axis;
use Happenv\FilamentEnhancedCharts\Option\Axis\ValueAxis;
use Happenv\FilamentEnhancedCharts\Option\Component\AngleAxis;
use Happenv\FilamentEnhancedCharts\Option\Component\AxisPointer;
use Happenv\FilamentEnhancedCharts\Option\Component\Brush;
use Happenv\FilamentEnhancedCharts\Option\Component\Calendar;
use Happenv\FilamentEnhancedCharts\Option\Component\Dataset;
use Happenv\FilamentEnhancedCharts\Option\Component\DataZoom;
use Happenv\FilamentEnhancedCharts\Option\Component\Geo;
use Happenv\FilamentEnhancedCharts\Option\Component\Graphic;
use Happenv\FilamentEnhancedCharts\Option\Component\Grid;
use Happenv\FilamentEnhancedCharts\Option\Component\Legend;
use Happenv\FilamentEnhancedCharts\Option\Component\Matrix;
use Happenv\FilamentEnhancedCharts\Option\Component\Parallel;
use Happenv\FilamentEnhancedCharts\Option\Component\ParallelAxis;
use Happenv\FilamentEnhancedCharts\Option\Component\Polar;
use Happenv\FilamentEnhancedCharts\Option\Component\Radar;
use Happenv\FilamentEnhancedCharts\Option\Component\RadiusAxis;
use Happenv\FilamentEnhancedCharts\Option\Component\SingleAxis;
use Happenv\FilamentEnhancedCharts\Option\Component\Title;
use Happenv\FilamentEnhancedCharts\Option\Component\Toolbox;
use Happenv\FilamentEnhancedCharts\Option\Component\Tooltip;
use Happenv\FilamentEnhancedCharts\Option\Component\VisualMap;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasAnimation;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Happenv\FilamentEnhancedCharts\Option\Series\Series;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Illuminate\Support\Traits\Conditionable;

final class Option implements Node
{
    use Conditionable;
    use HasAnimation;
    use HasRaw;

    /** @var list<string|array<mixed>> */
    private array $color = [];

    private string | array | null $backgroundColor = null;

    private ?bool $scrollable = null;

    /** @var list<Legend> */
    private array $legend = [];

    private ?Tooltip $tooltip = null;

    private ?Toolbox $toolbox = null;

    private ?AxisPointer $axisPointer = null;

    private ?Brush $brush = null;

    /** @var list<Title> */
    private array $title = [];

    /** @var list<Grid> */
    private array $grid = [];

    /** @var list<Axis> */
    private array $xAxis = [];

    /** @var list<Axis> */
    private array $yAxis = [];

    /** @var list<Radar> */
    private array $radar = [];

    private ?Parallel $parallel = null;

    /** @var list<ParallelAxis> */
    private array $parallelAxis = [];

    /** @var list<VisualMap> */
    private array $visualMap = [];

    /** @var list<DataZoom> */
    private array $dataZoom = [];

    /** @var list<Polar> */
    private array $polar = [];

    /** @var list<AngleAxis> */
    private array $angleAxis = [];

    /** @var list<RadiusAxis> */
    private array $radiusAxis = [];

    /** @var list<SingleAxis> */
    private array $singleAxis = [];

    /** @var list<Calendar> */
    private array $calendar = [];

    /** @var list<Geo> */
    private array $geo = [];

    /** @var list<Matrix> */
    private array $matrix = [];

    /** @var list<Dataset> */
    private array $dataset = [];

    /** @var list<Graphic|Graphic\GraphicElement> */
    private array $graphic = [];

    /** @var list<Series> */
    private array $series = [];

    public static function make(): self
    {
        return new self;
    }

    /**
     * A batteries-included cartesian preset: an axis-trigger tooltip and a top
     * legend, ready for an xAxis + series (the value y-axis is defaulted). Use
     * make() instead when you want a blank slate.
     */
    public static function cartesian(): self
    {
        return self::make()
            ->tooltip(Tooltip::make()->trigger('axis'))
            ->legend(Legend::make()->top(0));
    }

    /**
     * The chart's color palette. Each entry is a CSS color, a Filament palette
     * (Color::Amber → its 500 shade), or an ECharts gradient object.
     *
     * @param  string|array<mixed>  ...$color
     */
    public function color(string | array ...$color): self
    {
        $this->color = array_merge($this->color, array_map(Normalize::color(...), $color));

        return $this;
    }

    /** @param string|array<mixed> $backgroundColor A CSS color, Filament palette, or gradient object. */
    public function backgroundColor(string | array $backgroundColor): self
    {
        $this->backgroundColor = Normalize::color($backgroundColor);

        return $this;
    }

    /**
     * Let page-scroll pass through the chart instead of being captured by it.
     * When enabled, `inside` dataZoom stops zooming/panning on the mouse wheel
     * (drag-to-pan via `moveOnMouseMove` is kept) and any `roam` on a series or
     * the geo component is downgraded to `'move'` (drag-pan only, no wheel-zoom)
     * — so scrolling the wheel over the chart scrolls the page. Pass `false` for
     * a chart where wheel-zoom is the point (e.g. a full-bleed interactive map).
     */
    public function scrollable(bool $scrollable = true): self
    {
        $this->scrollable = $scrollable;

        return $this;
    }

    /**
     * Apply a caller-supplied scrollable default WITHOUT overriding an explicit
     * `scrollable()` call — lets a host (e.g. EnhancedChartWidget) default every chart
     * one way while a single chart still opts out. Internal wiring.
     */
    public function applyScrollableDefault(bool $default): self
    {
        $this->scrollable ??= $default;

        return $this;
    }

    public function legend(Legend ...$legend): self
    {
        $this->legend = array_merge($this->legend, $legend);

        return $this;
    }

    public function tooltip(Tooltip $tooltip): self
    {
        $this->tooltip = $tooltip;

        return $this;
    }

    public function toolbox(Toolbox $toolbox): self
    {
        $this->toolbox = $toolbox;

        return $this;
    }

    /** A global axis pointer (shared crosshair across the chart's axes). */
    public function axisPointer(AxisPointer $axisPointer): self
    {
        $this->axisPointer = $axisPointer;

        return $this;
    }

    /** The brush component for region selection (pair with a Toolbox brush button). */
    public function brush(Brush $brush): self
    {
        $this->brush = $brush;

        return $this;
    }

    public function title(Title ...$title): self
    {
        $this->title = array_merge($this->title, $title);

        return $this;
    }

    public function grid(Grid ...$grids): self
    {
        $this->grid = array_merge($this->grid, $grids);

        return $this;
    }

    public function xAxis(Axis ...$axes): self
    {
        $this->xAxis = array_merge($this->xAxis, $axes);

        return $this;
    }

    public function yAxis(Axis ...$axes): self
    {
        $this->yAxis = array_merge($this->yAxis, $axes);

        return $this;
    }

    /**
     * One radar coordinate, or several for a multi-radar chart (pair each
     * RadarSeries with `radarIndex()`). A single radar emits as an object.
     */
    public function radar(Radar ...$radar): self
    {
        $this->radar = array_merge($this->radar, $radar);

        return $this;
    }

    public function parallel(Parallel $parallel): self
    {
        $this->parallel = $parallel;

        return $this;
    }

    public function parallelAxis(ParallelAxis ...$axes): self
    {
        $this->parallelAxis = array_merge($this->parallelAxis, $axes);

        return $this;
    }

    public function visualMap(VisualMap ...$visualMap): self
    {
        $this->visualMap = array_merge($this->visualMap, $visualMap);

        return $this;
    }

    public function dataZoom(DataZoom ...$dataZoom): self
    {
        $this->dataZoom = array_merge($this->dataZoom, $dataZoom);

        return $this;
    }

    public function polar(Polar ...$polar): self
    {
        $this->polar = array_merge($this->polar, $polar);

        return $this;
    }

    public function angleAxis(AngleAxis ...$axes): self
    {
        $this->angleAxis = array_merge($this->angleAxis, $axes);

        return $this;
    }

    public function radiusAxis(RadiusAxis ...$axes): self
    {
        $this->radiusAxis = array_merge($this->radiusAxis, $axes);

        return $this;
    }

    public function singleAxis(SingleAxis ...$axes): self
    {
        $this->singleAxis = array_merge($this->singleAxis, $axes);

        return $this;
    }

    public function calendar(Calendar ...$calendar): self
    {
        $this->calendar = array_merge($this->calendar, $calendar);

        return $this;
    }

    public function geo(Geo ...$geo): self
    {
        $this->geo = array_merge($this->geo, $geo);

        return $this;
    }

    public function matrix(Matrix ...$matrix): self
    {
        $this->matrix = array_merge($this->matrix, $matrix);

        return $this;
    }

    /** A dataset (or several) feeding series via datasetIndex()/encode(). Always emitted as a list. */
    public function dataset(Dataset ...$dataset): self
    {
        $this->dataset = array_merge($this->dataset, $dataset);

        return $this;
    }

    /**
     * Graphic elements overlaid on the chart. Accepts `GraphicElement` builders
     * directly, or a `Graphic` wrapper. ECharts' top-level `graphic` is a single
     * `{elements: [...]}` object, so everything passed (across calls) is merged
     * into one elements list.
     */
    public function graphic(Graphic | Graphic\GraphicElement ...$graphic): self
    {
        $this->graphic = array_merge($this->graphic, $graphic);

        return $this;
    }

    public function series(Series ...$series): self
    {
        $this->series = array_merge($this->series, $series);

        return $this;
    }

    public function toArray(): array
    {
        $option = [];

        if ($this->color !== []) {
            $option['color'] = $this->color;
        }
        if ($this->backgroundColor !== null) {
            $option['backgroundColor'] = $this->backgroundColor;
        }
        $legend = $this->emitNodes($this->legend);
        if ($legend !== null) {
            $option['legend'] = $legend;
        }
        if ($this->tooltip instanceof Tooltip) {
            $option['tooltip'] = $this->tooltip->toArray();
        }
        if ($this->toolbox instanceof Toolbox) {
            $option['toolbox'] = $this->toolbox->toArray();
        }
        if ($this->axisPointer instanceof AxisPointer) {
            $option['axisPointer'] = $this->axisPointer->toArray();
        }
        if ($this->brush instanceof Brush) {
            $option['brush'] = $this->brush->toArray();
        }
        $title = $this->emitNodes($this->title);
        if ($title !== null) {
            $option['title'] = $title;
        }
        $grid = $this->emitNodes($this->grid);
        if ($grid !== null) {
            $option['grid'] = $grid;
        }
        // A cartesian chart needs both axes; default the missing one to a value
        // axis when only its partner was given (x for a horizontal category-y
        // chart, y for the usual category-x chart). Override with ->xAxis(...)/
        // ->yAxis(...) for a different axis (e.g. a CategoryAxis for a heatmap).
        $xAxisSource = $this->xAxis === [] && $this->yAxis !== [] ? [ValueAxis::make()] : $this->xAxis;
        $xAxis = $this->emitAxes($xAxisSource);
        if ($xAxis !== null) {
            $option['xAxis'] = $xAxis;
        }
        $yAxisSource = $this->yAxis === [] && $this->xAxis !== [] ? [ValueAxis::make()] : $this->yAxis;
        $yAxis = $this->emitAxes($yAxisSource);
        if ($yAxis !== null) {
            $option['yAxis'] = $yAxis;
        }
        $polar = $this->emitNodes($this->polar);
        if ($polar !== null) {
            $option['polar'] = $polar;
        }
        $angleAxis = $this->emitNodes($this->angleAxis);
        if ($angleAxis !== null) {
            $option['angleAxis'] = $angleAxis;
        }
        $radiusAxis = $this->emitNodes($this->radiusAxis);
        if ($radiusAxis !== null) {
            $option['radiusAxis'] = $radiusAxis;
        }
        $radar = $this->emitNodes($this->radar);
        if ($radar !== null) {
            $option['radar'] = $radar;
        }
        $geo = $this->emitNodes($this->geo);
        if ($geo !== null) {
            $option['geo'] = $geo;
        }
        $calendar = $this->emitNodes($this->calendar);
        if ($calendar !== null) {
            $option['calendar'] = $calendar;
        }
        $matrix = $this->emitNodes($this->matrix);
        if ($matrix !== null) {
            $option['matrix'] = $matrix;
        }
        $singleAxis = $this->emitNodes($this->singleAxis);
        if ($singleAxis !== null) {
            $option['singleAxis'] = $singleAxis;
        }
        if ($this->parallel instanceof Parallel) {
            $option['parallel'] = $this->parallel->toArray();
        }
        if ($this->parallelAxis !== []) {
            $option['parallelAxis'] = array_map(
                static fn (ParallelAxis $a): array => $a->toArray(),
                $this->parallelAxis,
            );
        }
        $visualMap = $this->emitNodes($this->visualMap);
        if ($visualMap !== null) {
            $option['visualMap'] = $visualMap;
        }
        $dataZoom = $this->emitNodes($this->dataZoom);
        if ($dataZoom !== null) {
            $option['dataZoom'] = $dataZoom;
        }
        if ($this->dataset !== []) {
            $option['dataset'] = array_map(static fn (Dataset $d): array => $d->toArray(), $this->dataset);
        }
        if ($this->graphic !== []) {
            $option['graphic'] = $this->emitGraphic($this->graphic);
        }
        if ($this->series !== []) {
            $option['series'] = array_map(static fn (Series $s): array => $s->toArray(), $this->series);
        }

        $option = array_merge($option, $this->animationArray());

        $option = $this->mergeRaw($option);

        return $this->scrollable ? $this->yieldWheelToPage($option) : $option;
    }

    /**
     * Rewrite the wheel-capturing bits of the option so the mouse wheel scrolls
     * the PAGE instead of the chart: `inside` dataZoom stops wheel zoom/pan
     * (drag-pan stays), and every `roam` is downgraded to `'move'` (drag-pan,
     * no wheel-zoom). Leaves sliders, drag, and everything else untouched.
     *
     * @param  array<string, mixed>  $option
     * @return array<string, mixed>
     */
    private function yieldWheelToPage(array $option): array
    {
        if (isset($option['dataZoom'])) {
            $option['dataZoom'] = $this->mapComponents($option['dataZoom'], static function (array $dataZoom): array {
                if (($dataZoom['type'] ?? null) === 'inside') {
                    $dataZoom['zoomOnMouseWheel'] = false;
                    $dataZoom['moveOnMouseWheel'] = false;
                }

                return $dataZoom;
            });
        }

        foreach (['series', 'geo'] as $key) {
            if (isset($option[$key])) {
                $option[$key] = $this->mapComponents($option[$key], static function (array $node): array {
                    if (($node['roam'] ?? false) !== false) {
                        $node['roam'] = 'move';
                    }

                    return $node;
                });
            }
        }

        return $option;
    }

    /**
     * Apply a callback to a component slot that ECharts allows as either a single
     * object or a list of them (dataZoom, geo, series), preserving that shape.
     *
     * @param  array<string, mixed>|list<array<string, mixed>>  $value
     * @param  callable(array<string, mixed>): array<string, mixed>  $callback
     * @return array<string, mixed>|list<array<string, mixed>>
     */
    private function mapComponents(array $value, callable $callback): array
    {
        if (array_is_list($value)) {
            return array_map(
                static fn (mixed $node): mixed => is_array($node) ? $callback($node) : $node,
                $value,
            );
        }

        return $callback($value);
    }

    /**
     * The top-level `graphic` option is a single `{elements: [...]}` object —
     * a LIST of `{elements}` wrappers is not a valid ECharts shape, so multiple
     * `Graphic` wrappers / bare elements are merged into one elements list. A
     * single `Graphic` emits verbatim (its raw() keys included).
     *
     * @param  list<Graphic|Graphic\GraphicElement>  $graphic
     * @return array<string, mixed>|object
     */
    private function emitGraphic(array $graphic): array | object
    {
        if (count($graphic) === 1 && $graphic[0] instanceof Graphic) {
            return $this->emitSingle($graphic[0]);
        }

        $elements = [];
        foreach ($graphic as $item) {
            if ($item instanceof Graphic) {
                $elements = array_merge($elements, $item->toArray()['elements'] ?? []);
            } else {
                $elements[] = $item->toArray();
            }
        }

        return ['elements' => $elements];
    }

    /**
     * NONE emits nothing, ONE emits the axis object, MULTIPLE emits a list —
     * mirrors ECharts' own single-vs-array axis convention.
     *
     * @param  list<Axis>  $axes
     * @return array<mixed>|object|null
     */
    private function emitAxes(array $axes): array | object | null
    {
        return match (count($axes)) {
            0 => null,
            1 => $this->emitSingle($axes[0]),
            default => array_map(static fn (Axis $a): array => $a->toArray(), $axes),
        };
    }

    /**
     * NONE emits nothing, ONE emits the object, MULTIPLE emits a list — the same
     * single-vs-array convention as ECharts' coordinate-system components.
     *
     * @param  list<Node>  $nodes
     * @return array<mixed>|object|null
     */
    private function emitNodes(array $nodes): array | object | null
    {
        return match (count($nodes)) {
            0 => null,
            1 => $this->emitSingle($nodes[0]),
            default => array_map(static fn (Node $n): array => $n->toArray(), $nodes),
        };
    }

    /**
     * A single ECharts component/coordinate system is always an OBJECT. An empty
     * one must serialize as `{}`, not `[]` — PHP's empty array would JSON-encode
     * to a list, which ECharts reads as "zero of this component" (e.g. an empty
     * `polar: []` gives a polar series no coordinate system, so it never renders).
     *
     * @return array<mixed>|object
     */
    private function emitSingle(Node $node): array | object
    {
        $array = $node->toArray();

        return $array === [] ? (object) [] : $array;
    }
}
