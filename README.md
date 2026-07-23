# Apache ECharts for Filament

[![Latest Version on Packagist](https://img.shields.io/packagist/v/happenv-com/filament-enhanced-charts.svg?style=flat-square)](https://packagist.org/packages/happenv-com/filament-enhanced-charts)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/happenv-com/filament-enhanced-charts/tests.yml?branch=1.x&label=tests&style=flat-square)](https://github.com/happenv-com/filament-enhanced-charts/actions?query=workflow%3A"Run+Tests"+branch%3A1.x)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/happenv-com/filament-enhanced-charts/pint.yml?branch=1.x&label=code%20style&style=flat-square)](https://github.com/happenv-com/filament-enhanced-charts/actions?query=workflow%3A"Fix+PHP+Code+Styling"+branch%3A1.x)
[![Total Downloads](https://img.shields.io/packagist/dt/happenv-com/filament-enhanced-charts.svg?style=flat-square)](https://packagist.org/packages/happenv-com/filament-enhanced-charts)

[Apache ECharts](https://echarts.apache.org/) integration for [Filament](https://filamentphp.com/):
dashboard **widgets** and table **columns** driven by a fully typed, fluent PHP option model.

```php
class OrdersChart extends EnhancedChartWidget
{
    protected static ?string $heading = 'Orders per day';

    protected function getOption(): Option
    {
        return Option::cartesian()
            ->xAxis(CategoryAxis::make()->data(['Mon', 'Tue', 'Wed']))
            ->series(LineSeries::make()->name('Orders')->smooth()->data([12, 20, 15]));
    }
}
```

- **Typed option model** — the ECharts option tree mirrored as fluent PHP builders. If you know an
  ECharts option, you know the method. Everything not (yet) modelled stays reachable through a
  `->raw()` escape hatch on every node.
- **22 series types** — line, bar, pie, scatter, effectScatter, candlestick, boxplot, heatmap,
  radar, gauge, funnel, sankey, sunburst, treemap, tree, graph, parallel, themeRiver, pictorialBar,
  map, lines, and fully custom `renderItem` series.
- **Every coordinate system** — cartesian grids, polar, radar, geo/maps, calendar, singleAxis,
  parallel, matrix — plus datasets with transforms, visual maps, data zoom, toolbox, brush and
  graphic elements.
- **Filament-native** — panel theming, automatic dark mode, Filament `Color` palettes, `RawJs`
  for client-side callbacks, Livewire polling, deferred loading, filters.
- **Table columns** — sparklines, candlesticks and donut pies inside table cells via
  `EnhancedChartColumn`, or any custom per-record chart.

## Requirements

- PHP `^8.4`
- Filament `^4.0` or `^5.0`

## Installation

```bash
composer require happenv-com/filament-enhanced-charts
```

Register the plugin on each Filament panel that should render charts:

```php
use Happenv\FilamentEnhancedCharts\FilamentEnhancedChartsPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            FilamentEnhancedChartsPlugin::make(),
        ]);
}
```

After installing or upgrading, republish the compiled JavaScript assets:

```bash
php artisan filament:assets
```

Upgrading from 2.x? See [UPGRADE.md](UPGRADE.md).

## Your first widget

A chart widget is a regular Filament widget: extend `EnhancedChartWidget` and return an `Option` from
`getOption()`. That is the whole story.

```php
use Happenv\FilamentEnhancedCharts\Option\Axis\CategoryAxis;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\BarSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\LineSeries;
use Happenv\FilamentEnhancedCharts\Widgets\EnhancedChartWidget;

class BlogPostsChart extends EnhancedChartWidget
{
    protected static ?string $heading = 'Blog posts';

    protected function getOption(): Option
    {
        return Option::cartesian()
            ->xAxis(CategoryAxis::make()->data(['Jan', 'Feb', 'Mar']))
            ->series(
                LineSeries::make()->name('Posts')->smooth()->data([10, 15, 8]),
                BarSeries::make()->name('Comments')->data([45, 60, 32]),
            );
    }
}
```

Two entry points exist on `Option`:

- `Option::make()` — a blank slate.
- `Option::cartesian()` — a batteries-included preset for the common case: an axis-triggered
  tooltip and a top legend, ready for `->xAxis()` + `->series()`.

When an x-axis is set and no y-axis is given, a `ValueAxis` is defaulted for you. Set
`->yAxis(...)` explicitly when you need something else (e.g. a `CategoryAxis` for a heatmap).

## The option model

Everything under `Happenv\FilamentEnhancedCharts\Option\*` is a fluent builder that serializes to the
exact [ECharts option](https://echarts.apache.org/en/option.html) shape. This package deliberately
does **not** re-document every chart type — the ECharts docs and
[examples gallery](https://echarts.apache.org/examples/en/) are the reference; the builders mirror
them method-for-method.

| Kind | Classes |
|---|---|
| Root | `Option` |
| Axes | `Axis\CategoryAxis`, `Axis\ValueAxis`, `Axis\TimeAxis`, `Axis\LogAxis` |
| Series | `Series\LineSeries`, `BarSeries`, `PieSeries`, `ScatterSeries`, `EffectScatterSeries`, `CandlestickSeries`, `BoxplotSeries`, `HeatmapSeries`, `RadarSeries`, `GaugeSeries`, `FunnelSeries`, `SankeySeries`, `SunburstSeries`, `TreemapSeries`, `TreeSeries`, `GraphSeries`, `ParallelSeries`, `ThemeRiverSeries`, `PictorialBarSeries`, `MapSeries`, `LinesSeries`, `CustomSeries` |
| Components | `Component\Legend`, `Tooltip`, `Title`, `Grid`, `DataZoom`, `VisualMap`, `Toolbox` (+ `Toolbox\*` features), `AxisPointer`, `Brush`, `Polar`, `AngleAxis`, `RadiusAxis`, `Radar`, `Geo`, `Calendar`, `SingleAxis`, `Parallel`, `ParallelAxis`, `Matrix`, `Dataset`, `Transform`, `Graphic` (+ `Graphic\*` elements) |
| Styles | `Style\ItemStyle`, `LineStyle`, `AreaStyle`, `Label`, `Emphasis` |
| Marks | `Mark\MarkLine`, `MarkPoint`, `MarkArea` |
| Data | `DataPoint`, `SankeyNode`, `SankeyLink` |

Conventions that hold across the whole tree:

- **`::make()` + fluent setters.** Every builder is created with `::make()` and configured by
  chaining; setters return `$this`.
- **One or many.** Slots that ECharts accepts as "an object or an array of objects" are variadic:
  `->grid(...)`, `->xAxis(...)`, `->title(...)`, `->series(...)`, `->visualMap(...)`, … Pass one or
  several; the emitted JSON follows ECharts' single-vs-array convention automatically.
- **Builders or arrays.** Sub-config setters accept the matching builder *or* a plain array:
  `->itemStyle(ItemStyle::make()->borderRadius(4))` and `->itemStyle(['borderRadius' => 4])` are
  equivalent.
- **Conditional building.** All builders use Laravel's `Conditionable`:
  `->when($this->filter === 'year', fn (Option $o) => $o->dataZoom(...))`.
- **Per-point configuration.** Wherever a data item can be more than a value, pass a `DataPoint`:
  `DataPoint::make(1048)->name('Search')->itemStyle(ItemStyle::make()->color('#c23531'))`.

### The `raw()` escape hatch

Every node (`Option`, each series, axis, component, `DataPoint`, …) accepts
`->raw(array $options)`. Raw options are **deep-merged over the typed output, raw wins** — so you
can reach any ECharts option the typed API doesn't model yet without abandoning the builders:

```php
LineSeries::make()
    ->data($values)
    ->raw(['universalTransition' => true]);
```

To skip the typed model entirely, return a raw tree from a blank `Option`:

```php
protected function getOption(): Option
{
    return Option::make()->raw([
        'xAxis' => ['type' => 'category', 'data' => ['A', 'B']],
        'series' => [['type' => 'bar', 'data' => [5, 7]]],
    ]);
}
```

Prefer the typed methods — `raw()` is the last resort, not the default.

## Formatters and JS callbacks

Strings passed to `formatter()`-style methods are treated as literal
[ECharts templates](https://echarts.apache.org/en/option.html#tooltip.formatter) and pass through
unchanged. For a real client-side function, pass Filament's `RawJs`: it is serialized as a marker
and revived into a genuine JS function in the browser.

```php
use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Option\Axis\ValueAxis;
use Happenv\FilamentEnhancedCharts\Option\Component\Tooltip;

Option::make()
    ->tooltip(Tooltip::make()->trigger('axis')->formatter('{b}: {c} pcs')) // template string
    ->yAxis(ValueAxis::make()->axisLabel(
        RawJs::make('(value) => (value / 1000) + "k"'),                    // executable JS
    ));
```

`RawJs` works anywhere in the tree — `renderItem` on a `CustomSeries`, `symbolSize` callbacks,
`animationDelay` functions, `labelLayout`, colors-by-callback, and so on. Inside these callbacks
the `echarts` global is available, so gallery snippets using `echarts.format.addCommas(...)` or
`echarts.graphic.clipRectByRect(...)` port verbatim.

## Numbers

Anywhere a number is accepted you may pass a native `\BcMath\Number` instead of
`int|float|string`; it is serialized as an exact numeric literal rather than a lossy float.

## Feeding data from Eloquent

A series' `data()` and an axis' `data()` take any `iterable`, so a `Collection` works — but the
**shape** matters:

- **A positional list** (a bare `pluck` or an array) → pass it straight to `data()`. Keys are
  dropped (the series/axis is positional), so a keyed Collection never mis-serializes to an object:

  ```php
  $totals = Order::query()->orderBy('day')->pluck('total');   // [120, 90, 140, …]
  LineSeries::make()->data($totals);
  ```

- **A keyed map** where the key is the label (`pluck('total', 'channel')`, a `groupBy` count) → use
  `ChartData::fromPairs()` to split it into labels + values:

  ```php
  use Happenv\FilamentEnhancedCharts\Data\ChartData;

  $data = ChartData::fromPairs(Order::query()->pluck('total', 'channel')); // ['B2B' => 60, …]

  Option::make()
      ->series(PieSeries::make()->data($data->toDataPoints()));            // named slices
  // or, for a cartesian chart:
  Option::cartesian()
      ->xAxis(CategoryAxis::make()->data($data->labels()))
      ->series(BarSeries::make()->data($data->values()));
  ```

- **Tabular rows for multiple series** → build a `Dataset` and let each series `encode()` its
  columns. `Dataset::fromModels()` projects a query result to just the chart columns (never the
  full attribute bag) — don't hand a `Collection<Model>` to `data()`:

  ```php
  Option::make()
      ->dataset(Dataset::fromModels(
          Order::query()->selectRaw('day, sum(total) total, channel')->get(),
          columns: ['day', 'total', 'channel'],
      ))
      ->series(LineSeries::make()->encode(x: 'day', y: 'total'));
  ```

### Time series

For "per day/week/month" aggregations, [`flowframe/laravel-trend`](https://github.com/flowframe/laravel-trend)
is the recommended companion: it handles the per-driver date bucketing (Postgres/MySQL/MariaDB/
SQLite) and gap-fills empty periods with zeros. It is an optional `suggest` — install it only if you
need time series:

```bash
composer require flowframe/laravel-trend
```

Feed its result straight to `ChartData::fromTimeSeries()`:

```php
use Flowframe\Trend\Trend;
use Happenv\FilamentEnhancedCharts\Data\ChartData;

$data = ChartData::fromTimeSeries(
    Trend::query($this->scopedOrders())
        ->between(now()->subDays(30), now())
        ->perDay()
        ->sum('total'),
);

Option::cartesian()
    ->xAxis(CategoryAxis::make()->data($data->labels()))
    ->series(LineSeries::make()->smooth()->data($data->values()));
```

`ChartData::fromTimeSeries()` reads `date`/`aggregate` off each row with `data_get()`, so it is
decoupled from the trend package — any `Collection` of rows carrying a label and a value column
works (pass `labelKey:`/`valueKey:` for different column names).

### Shortcut: a chart from just data + a type

When the chart is a single series of one of the common types, the `HasChartData` trait writes
`getOption()` for you — declare the data and (optionally) the type, nothing else:

```php
use Happenv\FilamentEnhancedCharts\Concerns\HasChartData;
use Happenv\FilamentEnhancedCharts\Data\ChartData;
use Happenv\FilamentEnhancedCharts\Enums\ChartType;
use Happenv\FilamentEnhancedCharts\Widgets\EnhancedChartWidget;

class SalesByChannelChart extends EnhancedChartWidget
{
    use HasChartData;

    protected static ?string $heading = 'Sales by channel';

    protected function getData(): ChartData
    {
        return ChartData::fromPairs(Order::query()->pluck('total', 'channel'));
    }

    protected function chartType(): ChartType
    {
        return ChartType::Bar; // Line (default), Area, Bar, or Pie
    }
}
```

`ChartType` is a curated set — `Line`, `Area`, `Bar`, `Pie` — that maps cleanly onto the
`labels + values` (or, for pie, the named `DataPoint`) shape. The trait builds a cartesian option
(category x-axis + value y-axis) for the axis types and an axis-less option for pie. The moment you
want more — a second series, custom styling, marks, a scatter's x/y pairs — implement `getOption()`
directly with the typed builders; this trait is only the shallow-end shortcut.

## Colors

Color setters (`color()`, `backgroundColor()`, `borderColor()`, `shadowColor()`, …) accept:

- any CSS color string (`'#7c3aed'`, `'rgb(124, 58, 237)'`),
- an ECharts gradient/pattern array,
- a **Filament palette** — `Color::Amber` resolves to its 500 shade, and Filament's `oklch(...)`
  values are converted to `rgb()` so ECharts can both paint them and derive hover shades:

```php
use Filament\Support\Colors\Color;

BarSeries::make()
    ->color(Color::Emerald)          // 500 shade
    ->data($values);

LineSeries::make()
    ->color(Color::Amber[600])       // an explicit shade
    ->data($values);
```

## Dark mode

Dark mode is automatic. Charts render transparent over the Filament card, and when the panel is in
dark mode the package overlays a theme layer client-side — axis lines/labels, split lines, legend,
title, tooltip, visual maps, parallel axes and outside series labels are all lightened for
readability. The theme reapplies live when the user toggles light/dark. You write the option once;
both modes just work.

Tip: skip forced `backgroundColor` and hardcoded text colors in your options — inherit the card
and let the theme adapt.

## Page scrolling

By default the mouse wheel scrolls the **page**, not the chart: `inside` data zoom stops
wheel-zooming (drag-to-pan is kept) and any `roam` is downgraded to drag-pan. That way a dashboard
full of charts never traps the user's scroll.

Opt a chart back into wheel-zoom (e.g. a full-bleed map) on the widget:

```php
protected static bool $scrollable = false;
```

or per option, which wins over the widget default:

```php
Option::make()->scrollable(false);
```

## Widget configuration

```php
protected static ?string $heading = 'Revenue';        // or override getHeading()
protected static ?string $subheading = 'Last 30 days'; // or getSubheading()
protected static bool $isCollapsible = true;           // or isCollapsible()
protected static int $contentHeight = 400;             // px, default 300; or getContentHeight()
protected static ?string $footer = 'Updated hourly';   // or getFooter() (string|Htmlable|View)
protected static string $chartId = 'revenueChart';     // stable DOM id, optional
protected static string $renderer = 'canvas';          // or 'svg'; or getRenderer()
protected int | string | array $columnSpan = 'full';
```

The header disappears entirely when no heading, subheading or filters are set.

### Deferred loading

Don't hold up the page for a slow query:

```php
protected static bool $deferLoading = true;

protected function getOption(): Option
{
    if (! $this->readyToLoad) {
        return Option::make();
    }

    return Option::cartesian()->series(/* the expensive part */);
}
```

### Loading indicator

```php
protected static ?string $loadingIndicator = 'Loading…'; // or getLoadingIndicator() returning a View
```

### Live updating (polling)

Widgets poll every 5 seconds by default. Change or disable it with an instance property:

```php
protected ?string $pollingInterval = '10s';

protected ?string $pollingInterval = null; // disable
```

Updates are diffed server-side — the chart only re-renders when the resolved options actually
changed.

## Filtering chart data

### Single select

Return options from `getFilters()` and read the active value from `$this->filter`:

```php
public ?string $filter = 'month';

protected function getFilters(): ?array
{
    return [
        'week' => 'Last week',
        'month' => 'Last month',
        'year' => 'This year',
    ];
}

protected function getOption(): Option
{
    $data = match ($this->filter) {
        'week' => $this->weekly(),
        'month' => $this->monthly(),
        'year' => $this->yearly(),
    };

    return Option::cartesian()->series(LineSeries::make()->data($data));
}
```

A select appears in the widget header and the chart updates live on change.

### Filter schema

For a richer filter form, implement the package's `HasFiltersSchema` **contract** and use
Filament's chart-widget filters trait; filter values arrive in `$this->filters`:

```php
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;
use Happenv\FilamentEnhancedCharts\Contracts\HasFiltersSchema as HasFiltersSchemaContract;
use Happenv\FilamentEnhancedCharts\Widgets\EnhancedChartWidget;

class BlogPostsChart extends EnhancedChartWidget implements HasFiltersSchemaContract
{
    use HasFiltersSchema;

    public function filtersSchema(Schema $schema): Schema
    {
        return $schema->components([
            DatePicker::make('date_start')->default(now()->subMonth()),
            DatePicker::make('date_end')->default(now()),
        ]);
    }

    public function updatedInteractsWithSchemas(string $statePath): void
    {
        parent::updatedInteractsWithSchemas($statePath);

        $this->updateOptions();
    }

    protected function getOption(): Option
    {
        $start = $this->filters['date_start'] ?? null;
        $end = $this->filters['date_end'] ?? null;

        // ...
    }
}
```

Implementing the contract is what makes the filter dropdown render — don't skip it.

## Maps (GeoJSON)

A `MapSeries` or `geo` component needs its map registered client-side. Return `name => url` pairs
from `getMaps()`; each is fetched and passed to `echarts.registerMap()` before first paint:

```php
use Happenv\FilamentEnhancedCharts\Option\Component\VisualMap;
use Happenv\FilamentEnhancedCharts\Option\Series\MapSeries;

/** @return array<string, string> */
public function getMaps(): array
{
    return ['world' => asset('geo/world.json')];
}

protected function getOption(): Option
{
    return Option::make()
        ->visualMap(VisualMap::continuous()->min(0)->max(100)->calculable())
        ->series(MapSeries::make()->map('world')->roam()->data($rows));
}
```

## Charts in table cells

`EnhancedChartColumn` renders a small chart per row. Three presets cover the common cases; `chart()`
takes over for anything else. Return `null`/empty data to render nothing for that row.

```php
use Happenv\FilamentEnhancedCharts\Columns\EnhancedChartColumn;

EnhancedChartColumn::make('trend')
    ->sparkline(fn (Product $record): array => $record->daily_sales)
    ->fill(),                                      // area fill; ->bars() for bar sparklines

EnhancedChartColumn::make('ohlc')
    ->width(160)                                   // px, '100%', or a Closure; height(int) too
    ->candles(fn (Product $record): array => $record->ohlc_rows), // [open, close, low, high] rows

EnhancedChartColumn::make('mix')
    ->pie(fn (Order $record): array => ['B2B' => 60, 'B2C' => 40]), // donut

EnhancedChartColumn::make('custom')
    ->chart(fn (Server $record): ?Option => Option::make()
        ->series(GaugeSeries::make()->data([DataPoint::make($record->cpu)]))),
```

Cell charts ship with a sensible baseline (tooltip escaping the cell, no legend, tight margins)
and are disposed cleanly as rows leave the DOM. `->renderer('svg')` is available per column.
Custom `->chart()` closures can start from the same baseline via
`EnhancedChartColumn::cellOption()` (optionally passing the tooltip trigger, e.g. `'axis'`).

## Publishing views / translations / config

```bash
php artisan vendor:publish --tag="filament-enhanced-charts-views"
php artisan vendor:publish --tag="filament-enhanced-charts-translations"
php artisan vendor:publish --tag="filament-enhanced-charts-config"
```

## Testing

The package registers a Livewire `Testable` mixin with chart assertions for your app's tests:

```php
use function Pest\Livewire\livewire;

livewire(RevenueChart::class)
    ->assertChartSeriesCount(2)
    ->assertChartHasSeries('line')
    ->assertChartOptions(fn (array $options): bool => $options['series'][0]['smooth'] === true);
```

To run the package's own test suite:

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [elemind](https://github.com/elemind)
- Strongly inspired by [Leandro Ferreira's Apex Charts plugin](https://filamentphp.com/plugins/leandrocfe-apex-charts)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
