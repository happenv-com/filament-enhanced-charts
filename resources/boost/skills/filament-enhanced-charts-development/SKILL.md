---
name: filament-enhanced-charts-development
description: >-
  Build Apache ECharts dashboard widgets and table-cell charts in Filament 4/5 with
  happenv-com/filament-enhanced-charts. Use when writing an EnhancedChartWidget (getOption(): Option,
  Option::cartesian()/make(), LineSeries, BarSeries, PieSeries and the other *Series builders,
  CategoryAxis/ValueAxis/TimeAxis, Tooltip, Legend, DataZoom, VisualMap, Toolbox, MarkLine, DataPoint),
  shaping Eloquent data (ChartData::fromPairs/fromTimeSeries, Dataset::fromModels, encode()), the
  HasChartData/ChartType shortcut, raw() and RawJs formatters, Filament colors, filters (getFilters,
  the HasFiltersSchema contract), polling, deferred loading, maps (getMaps), EnhancedChartColumn
  (sparkline/candles/pie/chart), or testing with assertChartOptions/assertChartSeriesCount/assertChartHasSeries.
---

# Filament Enhanced Charts

Apache ECharts (v6) for Filament. A chart is a Filament widget that returns a **typed `Option` tree** from `getOption()`; the tree serializes to the exact [ECharts option](https://echarts.apache.org/en/option.html) array, which is pushed to the browser. The builders mirror ECharts method-for-method, so the ECharts docs are the reference for what an option does; this package decides how it is spelled in PHP. Everything not modelled is reachable with `->raw([...])` on every node. Dark mode, the app locale and Filament colors are handled for you.

## When to use this skill

- Writing or changing a chart widget (dashboard, resource page header/footer widgets).
- Turning query results into chart data.
- Adding filters, polling, deferred loading or a GeoJSON map to a chart.
- Putting a small chart in a table column.
- Testing a chart widget's resolved options.

## Installation

```bash
composer require happenv-com/filament-enhanced-charts
php artisan filament:assets   # after every install AND upgrade: the JS ships in resources/dist
```

```php
use Happenv\FilamentEnhancedCharts\FilamentEnhancedChartsPlugin;

$panel->plugins([FilamentEnhancedChartsPlugin::make()]);
```

The plugin is registered per panel as documented; the assets themselves are registered by the service provider, so a blank chart usually means stale assets (`filament:assets`), not a missing plugin. Requirements: PHP 8.4+, Laravel 12/13, Filament 4/5. `make:filament-enhanced-charts {name}` generates a widget from a stub but is fully interactive (chart type, resource, panel prompts); when working non-interactively, write the class by hand.

## A widget

```php
use Filament\Support\Colors\Color;
use Happenv\FilamentEnhancedCharts\Option\Axis\CategoryAxis;
use Happenv\FilamentEnhancedCharts\Option\Mark\MarkLine;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\BarSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\LineSeries;
use Happenv\FilamentEnhancedCharts\Widgets\EnhancedChartWidget;

class RevenueChart extends EnhancedChartWidget
{
    protected static ?string $heading = 'Revenue';
    protected static ?string $subheading = 'Last 7 days';
    protected static int $contentHeight = 320;          // px, default 300
    protected ?string $pollingInterval = null;           // default '5s'; null disables polling
    protected int | string | array $columnSpan = 'full';

    protected function getOption(): Option
    {
        return Option::cartesian()                        // axis tooltip + top legend preset
            ->xAxis(CategoryAxis::make()->data(['Mon', 'Tue', 'Wed']))
            ->series(                                     // y ValueAxis is defaulted
                LineSeries::make()->name('Orders')->smooth()->color(Color::Emerald)->data([12, 20, 15]),
                BarSeries::make()->name('Returns')->stack('total')->data([2, 1, 3])
                    ->markLine(MarkLine::make()->average()),
            );
    }
}
```

Implement `getOption()`. Do **not** override `getOptions()` (the internal sink that applies the scroll default and serializes); there is no `extraJsOptions()` either (that was the old `filament-echarts` v2 API, as was returning an array from `getOptions()`).

Other widget statics/hooks: `$headingIcon` / `getHeadingIcon()`, `getAfterHeader()` (Htmlable/View after the heading), `$isCollapsible`, `$footer` / `getFooter()`, `$renderer` (`'canvas'` default or `'svg'`), `$loadingIndicator`, `$deferLoading`, `$scrollable`, `$chartId`. Redeclared statics must keep the parent's exact type (`protected static ?string $chartId`, not `string`), or PHP fails with a fatal error.

## The option model

| Kind | Namespace `Happenv\FilamentEnhancedCharts\Option\...` |
|---|---|
| Root | `Option::make()` (blank), `Option::cartesian()` (preset) |
| Axes | `Axis\CategoryAxis`, `ValueAxis`, `TimeAxis`, `LogAxis` |
| Series | `Series\LineSeries`, `BarSeries`, `PieSeries`, `ScatterSeries`, `EffectScatterSeries`, `CandlestickSeries`, `BoxplotSeries`, `HeatmapSeries`, `RadarSeries`, `GaugeSeries`, `FunnelSeries`, `SankeySeries`, `ChordSeries`, `SunburstSeries`, `TreemapSeries`, `TreeSeries`, `GraphSeries`, `ParallelSeries`, `ThemeRiverSeries`, `PictorialBarSeries`, `MapSeries`, `LinesSeries`, `CustomSeries` |
| Components | `Component\Legend`, `Tooltip`, `Title`, `Grid`, `DataZoom::slider()/inside()`, `VisualMap::continuous()/piecewise()`, `Toolbox` + `Toolbox\SaveAsImage`, `DataView`, `MagicType`, `Restore`, ..., `AxisPointer`, `Brush`, `Polar`, `AngleAxis`, `RadiusAxis`, `Radar`, `Geo`, `Calendar`, `SingleAxis`, `Parallel`, `ParallelAxis`, `Matrix`, `Dataset`, `Transform`, `Graphic` + `Graphic\*` |
| Styles | `Style\ItemStyle`, `LineStyle`, `AreaStyle`, `Label`, `Emphasis` |
| Marks | `Mark\MarkLine` (`average()`, `min()`, `max()`, `at($y)`), `MarkPoint`, `MarkArea` (`band($from, $to)`) |
| Data | `DataPoint`, `SankeyNode::make($name)`, `SankeyLink::make($source, $target, $value)` |
| Enums | `Enums\TooltipTrigger`, `Symbol`, `Orient`, `RoseType`, `CoordinateSystem`, `Focus`, ... (setters take the enum or the ECharts string) |

Conventions:

- Every builder: `::make()` and chained setters. All use `Conditionable` (`->when($cond, fn ($o) => ...)`).
- Slots ECharts allows as "object or array" are **variadic and additive** on `Option`: `->series(a, b)`, `->xAxis(...)`, `->yAxis(...)`, `->grid(...)`, `->legend(...)`, `->dataZoom(...)`, `->visualMap(...)`, `->title(...)`. Calling `->series()` twice appends; one item emits as an object, several as a list. The first `->legend()` on a `cartesian()` option replaces the preset legend.
- `Option::tooltip()`, `toolbox()`, `parallel()` take one builder (not an array). Sub-configs on series/axes (`itemStyle()`, `label()`, `emphasis()`, `tooltip()`, `markLine()`, ...) take the builder **or** a plain array.
- If only one of `xAxis`/`yAxis` is set, the other defaults to a `ValueAxis`. A heatmap or horizontal bar needs both set explicitly (`yAxis(CategoryAxis::make()->data(...))`).
- Per-point config: `DataPoint::make(1048)->name('Search')->itemStyle(ItemStyle::make()->color('#c23531'))`.
- Exact numbers: pass `\BcMath\Number` anywhere a number is accepted.

### raw() and RawJs

```php
use Filament\Support\RawJs;

LineSeries::make()->data($values)->raw(['universalTransition' => true]); // deep-merged OVER the typed output, raw wins

Option::make()
    ->tooltip(Tooltip::make()->trigger('axis')->formatter('{b}: {c} pcs'))      // string = ECharts template
    ->yAxis(ValueAxis::make()->axisLabel(RawJs::make('(v) => (v / 1000) + "k"'))); // RawJs = real JS function
```

- `raw()` merges associative arrays recursively; a **list** in raw replaces the typed list wholesale. Prefer typed methods; `raw()` is the escape hatch. `Option::make()->raw($wholeEChartsArray)` is a valid way to port an existing option.
- `RawJs` works anywhere (formatters, `renderItem`, `symbolSize`, `animationDelay`, `labelLayout`); the `echarts` global is available inside. It is serialized as `['__js__' => '...']` and revived in the browser, so **never build RawJs from user input** (it is executed).

## Feeding data

`data()` on series and `CategoryAxis` accepts any iterable and **drops keys** (positional).

```php
use Happenv\FilamentEnhancedCharts\Data\ChartData;
use Happenv\FilamentEnhancedCharts\Option\Component\Dataset;

// List of values
LineSeries::make()->data(Order::query()->orderBy('day')->pluck('total'));

// Keyed map label => value
$data = ChartData::fromPairs(Order::query()->groupBy('channel')->selectRaw('channel, sum(total) total')->pluck('total', 'channel'));
Option::cartesian()->xAxis(CategoryAxis::make()->data($data->labels()))->series(BarSeries::make()->data($data->values()));
Option::make()->series(PieSeries::make()->radius(['40%', '70%'])->data($data->toDataPoints())); // named slices

// Time series (flowframe/laravel-trend is an optional suggest; any rows with date/aggregate work)
$data = ChartData::fromTimeSeries(Trend::model(Order::class)->between(now()->subDays(30), now())->perDay()->sum('total'));
$data = ChartData::fromTimeSeries($rows, labelKey: 'day', valueKey: 'total');

// Several series from tabular rows: a dataset + encode()
Option::cartesian()
    ->dataset(Dataset::fromModels($rows, ['day', 'orders', 'returns']))
    ->xAxis(CategoryAxis::make())
    ->series(
        LineSeries::make()->name('Orders')->encode(['x' => 'day', 'y' => 'orders']),
        LineSeries::make()->name('Returns')->encode(['x' => 'day', 'y' => 'returns']),
    );
```

- `encode()` takes an **array** (`['x' => ..., 'y' => ...]`), not named arguments.
- Never pass a `Collection` of models to `data()`: each model serializes to its full attribute array, which is sent to the browser. Project first (`pluck`, `ChartData`, `Dataset::fromModels($rows, $columns)`, which reads columns with `data_get()`, dotted paths included).
- Everything in the option tree is public in the page source. Do not chart columns the viewer may not see.

### Shortcut: HasChartData

```php
use Happenv\FilamentEnhancedCharts\Concerns\HasChartData;
use Happenv\FilamentEnhancedCharts\Enums\ChartType;

class SalesByChannelChart extends EnhancedChartWidget
{
    use HasChartData; // writes getOption() for you

    protected function getData(): ChartData { return ChartData::fromPairs(/* ... */); }

    protected function chartType(): ChartType { return ChartType::Bar; } // Line (default), Area, Bar, Pie
}
```

For a second series, styling or marks, drop the trait and write `getOption()`.

## Colors, theme, scrolling

- Color setters take a CSS string, an ECharts gradient array, or a Filament palette: `Color::Amber` (500 shade), `Color::Amber[600]`. Filament `oklch()` is converted to `rgb()` so hover shades work. `Option::make()->color(...)` sets the palette.
- Dark mode is automatic and toggles live. Do not force `backgroundColor` or hard-coded text colors; let the card and theme decide.
- By default the mouse wheel scrolls the page: `inside` data zoom loses wheel zoom and any `roam` becomes `'move'`. For a chart where wheel zoom matters (a map), set `protected static bool $scrollable = false;` or `Option::make()->scrollable(false)` (per option, wins).
- Labels, month/day names, toolbox and aria texts follow `app()->getLocale()`.

## Filters, polling, deferred loading

```php
public ?string $filter = 'month'; // give it a default your match() handles

protected function getFilters(): ?array
{
    return ['week' => 'Last week', 'month' => 'Last month', 'year' => 'This year'];
}
```

A richer filter form needs the package **contract** plus Filament's chart-widget trait (both named `HasFiltersSchema`):

```php
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;
use Happenv\FilamentEnhancedCharts\Contracts\HasFiltersSchema as HasFiltersSchemaContract;

class OrdersChart extends EnhancedChartWidget implements HasFiltersSchemaContract
{
    use HasFiltersSchema;

    public mixed $cachedData = null; // Filament's trait writes it; EnhancedChartWidget does not declare it

    public function filtersSchema(Schema $schema): Schema
    {
        return $schema->components([
            DatePicker::make('from')->default(now()->subMonth()),
            DatePicker::make('until')->default(now()),
        ]);
    }

    public function updatedInteractsWithSchemas(string $statePath): void
    {
        parent::updatedInteractsWithSchemas($statePath);

        $this->updateOptions();
    }

    protected function getOption(): Option
    {
        $from = $this->filters['from'] ?? null;
        // ...
    }
}
```

- Without `implements HasFiltersSchemaContract` the filter dropdown does not render.
- Only live filters work: the widget renders no apply/reset buttons, so Filament's deferred filters (`$hasDeferredFilters`) are not supported.
- The chart host is `wire:ignore`d; new options reach it only through the `updateOptions` browser event. A re-render pushes options whose hash changed (filters, dashboard page filters, any property `getOption()` reads). Do not try to re-render the chart with Blade.
- **Polling defaults to `'5s'`**, so `getOption()` (and its queries) runs every 5 seconds per widget. Set `protected ?string $pollingInterval = null;` for data that does not change live, or a longer interval. Unchanged options are not re-sent.
- `protected static bool $deferLoading = true;` renders the page first and loads the chart after; guard expensive work with `if (! $this->readyToLoad) { return Option::make(); }`.

## Maps

```php
public function getMaps(): array
{
    return ['world' => asset('geo/world.json')]; // fetched and echarts.registerMap()'d before first paint
}

protected function getOption(): Option
{
    return Option::make()
        ->visualMap(VisualMap::continuous()->min(0)->max(100)->calculable())
        ->series(MapSeries::make()->map('world')->roam()->data($rows));
}
```

## Charts in table cells

```php
use Happenv\FilamentEnhancedCharts\Columns\EnhancedChartColumn;

EnhancedChartColumn::make('trend')->sparkline(fn (Product $record): array => $record->daily_sales)->fill(); // ->bars()
EnhancedChartColumn::make('ohlc')->width(160)->height(40)->candles(fn ($record) => $record->ohlc_rows);   // [open, close, low, high]
EnhancedChartColumn::make('mix')->pie(fn ($record) => ['B2B' => 60, 'B2C' => 40]);                        // donut, 40x40
EnhancedChartColumn::make('cpu')->chart(fn (Server $record): ?Option => EnhancedChartColumn::cellOption()
    ->series(GaugeSeries::make()->data([DataPoint::make($record->cpu)])));
```

- The closure receives the record as its **first positional argument** (not Filament's named injection), so any parameter name works.
- Return `null` or blank data to render the column placeholder.
- Only an **int** `width()` sizes the chart (default 120x32, pie 40x40); a string or closure width only sizes the table cell. `height(int)` sets the chart height. Columns render `'svg'` by default (`->renderer('canvas')`).
- Start custom charts from `EnhancedChartColumn::cellOption($tooltipTrigger = 'item')` (tooltip appended to body, no legend, tight grid). To change its tooltip, patch it with `->raw(['tooltip' => [...]])`; a new `->tooltip()` replaces the baseline and the tooltip gets clipped by the cell.
- The option is embedded in every row's HTML: keep cell data small and eager-load what the closure reads.

## Gotchas

- `getFooter()` / `$footer` is output unescaped (`{!! !!}`); escape user data or return an `HtmlString` you built safely.
- Setting `protected static ?string $chartId` makes the DOM id fixed: two instances of the widget on one page then collide. Leave it unset unless something needs the id.
- `Option` setters append. Building an option in several helper methods is fine; calling the same builder method twice with "replacement" intent is not.
- Use `TimeAxis` for real dates (ECharts formats and spaces ticks); a `CategoryAxis` with formatted date strings is right for gap-filled buckets from `fromTimeSeries()`.
- After upgrading the package, run `php artisan filament:assets` again.

## Testing

The service provider registers Livewire `Testable` macros:

```php
use function Pest\Livewire\livewire;

livewire(RevenueChart::class)
    ->assertChartSeriesCount(2)
    ->assertChartHasSeries('line')
    ->assertChartOptions(fn (array $options): bool => $options['series'][0]['smooth'] === true);

livewire(OrdersChart::class)
    ->set('filter', 'year')
    ->assertDispatched('updateOptions')
    ->assertChartOptions(fn (array $options): bool => count($options['xAxis']['data']) === 12);
```

The assertions read the fully resolved array (the same one the browser gets). `RawJs` and `BcMath\Number` values appear as `['__js__' => '...']`, e.g. `isset($options['yAxis']['axisLabel']['formatter']['__js__'])`. A single axis or legend is an object, several are a list. For a deferred widget, `->call('loadWidget')` first.
