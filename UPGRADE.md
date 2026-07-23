# Upgrade Guide

## Upgrading from `happenv-com/filament-echarts` v2

`filament-enhanced-charts` 1.x is the renamed continuation of `filament-echarts`. The
biggest change is the option API: widgets no longer return a raw array — they return a
typed `Option` tree, and client-side JS moved in-tree via `RawJs`.

### 1. Swap the package

```bash
composer remove happenv-com/filament-echarts
composer require happenv-com/filament-enhanced-charts
```

Then republish the compiled JavaScript assets:

```bash
php artisan filament:assets
```

### 2. Rename classes

| v2 (`Happenv\FilamentECharts\…`) | 1.x (`Happenv\FilamentEnhancedCharts\…`) |
|---|---|
| `Widgets\EChartWidget` | `Widgets\EnhancedChartWidget` |
| `FilamentEChartsPlugin` | `FilamentEnhancedChartsPlugin` |

Update the plugin registration on every panel:

```php
use Happenv\FilamentEnhancedCharts\FilamentEnhancedChartsPlugin;

$panel->plugins([
    FilamentEnhancedChartsPlugin::make(),
]);
```

Charts inside table cells are provided by `Happenv\FilamentEnhancedCharts\Columns\EnhancedChartColumn`
(see the README's "Charts in table cells" section).

### 3. `getOptions(): array` → `getOption(): Option`

The abstract method on the widget changed. Instead of returning the ECharts option
shape as a nested array, return a typed `Option` built with the fluent builders
under `Happenv\FilamentEnhancedCharts\Option\*`.

Before (v2):

```php
use Happenv\FilamentECharts\Widgets\EChartWidget;

class OrdersChart extends EChartWidget
{
    protected static ?string $heading = 'Orders per day';

    protected function getOptions(): array
    {
        return [
            'tooltip' => ['trigger' => 'axis'],
            'xAxis' => ['type' => 'category', 'data' => ['Mon', 'Tue', 'Wed']],
            'yAxis' => ['type' => 'value'],
            'series' => [
                ['type' => 'line', 'name' => 'Orders', 'smooth' => true, 'data' => [12, 20, 15]],
            ],
        ];
    }
}
```

After (1.x):

```php
use Happenv\FilamentEnhancedCharts\Option\Axis\CategoryAxis;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\LineSeries;
use Happenv\FilamentEnhancedCharts\Widgets\EnhancedChartWidget;

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

Anything the typed builders don't model yet stays reachable through the `->raw(array)`
escape hatch on every node — a mechanical migration can start from
`Option::make()->raw($yourOldArray)` and adopt the typed methods incrementally.

### 4. `extraJsOptions()` is gone — `RawJs` moves in-tree

v2 kept client-side callbacks out-of-band in a single
`protected function extraJsOptions(): ?RawJs` blob that was merged over the option
array in the browser. That method no longer exists. Instead, pass Filament's
`RawJs` directly wherever the callback belongs in the option tree — formatters,
`renderItem`, `symbolSize` callbacks, and so on:

```php
use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Option\Axis\ValueAxis;
use Happenv\FilamentEnhancedCharts\Option\Component\Tooltip;

Option::make()
    ->tooltip(Tooltip::make()->trigger('axis')->formatter('{b}: {c} pcs')) // literal template
    ->yAxis(ValueAxis::make()->axisLabel(
        RawJs::make('(value) => (value / 1000) + "k"'),                    // executable JS
    ));
```

Plain strings passed to `formatter()`-style methods stay literal ECharts templates;
only `RawJs` becomes an executable function client-side.

### 5. `BcMath\Number` accepted natively

Anywhere a number is accepted (`data()`, axis min/max, mark values, …) you may pass a
native `\BcMath\Number` instead of `int|float|string`. It is serialized as an exact
numeric literal rather than a lossy float — no manual casting needed.

### 6. The `{__js__}` marker (relevant for tests)

`RawJs` and `BcMath\Number` values are serialized into the resolved options as
`['__js__' => '…']` markers, which the client-side reviver rebuilds into a real JS
function / an exact number in the browser. If your tests inspect the resolved
options — e.g. via the package's `assertChartOptions()` Livewire assertion — expect
those markers in place of the raw `RawJs`/`Number` values:

```php
livewire(RevenueChart::class)
    ->assertChartOptions(fn (array $options): bool => isset(
        $options['yAxis']['axisLabel']['formatter']['__js__'],
    ));
```
