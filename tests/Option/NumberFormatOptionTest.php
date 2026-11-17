<?php

declare(strict_types=1);

use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Option\Axis\CategoryAxis;
use Happenv\FilamentEnhancedCharts\Option\Axis\LogAxis;
use Happenv\FilamentEnhancedCharts\Option\Axis\TimeAxis;
use Happenv\FilamentEnhancedCharts\Option\Axis\ValueAxis;
use Happenv\FilamentEnhancedCharts\Option\Component\Tooltip;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\BarSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\LineSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\PieSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;
use Happenv\FilamentEnhancedCharts\Support\NumberFormat;

covers(Option::class);

/** @return array{style: string, locale: string, role: string, currency?: string, ...} */
function numberSpec(mixed $marker): array
{
    expect($marker)->toBeArray()->toHaveKey('__number__');

    return $marker['__number__'];
}

it('formats value axes, tooltips and cartesian data labels as locale-aware plain numbers by default', function (): void {
    app()->setLocale('pl');

    $option = Option::make()
        ->tooltip(Tooltip::make()->trigger('axis'))
        ->xAxis(CategoryAxis::make()->data(['a']))
        ->series(BarSeries::make()->data([1])->label(Label::make()->show()))
        ->toArray();

    expect(numberSpec($option['yAxis']['axisLabel']['formatter']))->toBe(['style' => 'decimal', 'locale' => 'pl', 'role' => 'axis'])
        ->and(numberSpec($option['tooltip']['valueFormatter'])['role'])->toBe('value')
        ->and(numberSpec($option['series'][0]['label']['formatter'])['role'])->toBe('label');
});

it('does not touch category and time axes, a tooltip that is absent or a label that is hidden', function (): void {
    $option = Option::make()
        ->xAxis(TimeAxis::make())
        ->yAxis(CategoryAxis::make()->data(['a']))
        ->series(BarSeries::make()->data([1]), PieSeries::make()->label(Label::make()->show()))
        ->toArray();

    expect($option['xAxis'])->not->toHaveKey('axisLabel')
        ->and($option['yAxis'])->not->toHaveKey('axisLabel')
        ->and($option)->not->toHaveKey('tooltip')
        ->and($option['series'][0])->not->toHaveKey('label')
        // A pie label is its name unless told otherwise; a number format must not replace it.
        ->and($option['series'][1]['label'])->not->toHaveKey('formatter');
});

it('formats log axes too', function (): void {
    expect(Option::make()->xAxis(LogAxis::make())->toArray()['xAxis']['axisLabel']['formatter'])->toHaveKey('__number__');
});

it('applies the format of a value axis, and keeps its other label settings', function (): void {
    $option = Option::make()
        ->xAxis(CategoryAxis::make()->data(['a']))
        ->yAxis(ValueAxis::make()->format(NumberFormat::currency('PLN'))->axisLabel(Label::make()->color('#f00')))
        ->toArray();

    expect(numberSpec($option['yAxis']['axisLabel']['formatter']))->toMatchArray(['style' => 'currency', 'currency' => 'PLN', 'role' => 'axis'])
        ->and($option['yAxis']['axisLabel']['color'])->toBe('#f00');
});

it('lets an explicit formatter win over a format and over the default', function (): void {
    $option = Option::make()
        ->tooltip(Tooltip::make()->trigger('axis')->format(NumberFormat::percent(fraction: true))->valueFormatter(RawJs::make('(v) => v + "!"')))
        ->xAxis(CategoryAxis::make()->data(['a']))
        ->yAxis(ValueAxis::make()->format(NumberFormat::compact())->axisLabel(RawJs::make('(v) => v + "x"')))
        ->series(
            LineSeries::make()->format(NumberFormat::compact())->label(Label::make()->show()->formatter('{c} pcs'))->tooltip(['valueFormatter' => ['__js__' => '(v) => "own"']]),
            BarSeries::make()->label(Label::make()->show()->formatter(RawJs::make('(p) => "own"'))),
        )
        ->toArray();

    expect($option['tooltip']['valueFormatter'])->toBe(['__js__' => '(v) => v + "!"'])
        ->and($option['yAxis']['axisLabel']['formatter'])->toBe(['__js__' => '(v) => v + "x"'])
        ->and($option['series'][0]['label']['formatter'])->toBe('{c} pcs')
        ->and($option['series'][0]['tooltip']['valueFormatter'])->toBe(['__js__' => '(v) => "own"'])
        ->and($option['series'][1]['label']['formatter'])->toBe(['__js__' => '(p) => "own"']);
});

it('formats every series with its own format, on its own axis', function (): void {
    $option = Option::cartesian()
        ->xAxis(CategoryAxis::make()->data(['Jan']))
        ->yAxis(ValueAxis::make()->format(NumberFormat::currency('PLN')), ValueAxis::make()->format(NumberFormat::number()->unit('szt.')))
        ->series(
            BarSeries::make()->name('Revenue')->format(NumberFormat::currency('PLN')),
            LineSeries::make()->name('Orders')->yAxisIndex(1)->format(NumberFormat::number()->unit('szt.')),
        )
        ->toArray();

    expect(numberSpec($option['series'][0]['tooltip']['valueFormatter']))->toMatchArray(['style' => 'currency', 'currency' => 'PLN', 'role' => 'value'])
        ->and(numberSpec($option['series'][1]['tooltip']['valueFormatter']))->toMatchArray(['style' => 'decimal', 'unit' => 'szt.'])
        ->and(numberSpec($option['yAxis'][0]['axisLabel']['formatter'])['style'])->toBe('currency')
        ->and(numberSpec($option['yAxis'][1]['axisLabel']['formatter'])['unit'])->toBe('szt.')
        // The tooltip still has the plain default for any series without a format of its own.
        ->and(numberSpec($option['tooltip']['valueFormatter'])['style'])->toBe('decimal');
});

it('formats a series label with its format only when the label is shown', function (): void {
    $option = Option::make()
        ->xAxis(CategoryAxis::make()->data(['a']))
        ->series(
            BarSeries::make()->format(NumberFormat::percent(fraction: true))->label(Label::make()->show()),
            BarSeries::make()->format(NumberFormat::percent(fraction: true)),
            PieSeries::make()->format(NumberFormat::compact())->label(Label::make()->show()),
        )
        ->toArray();

    expect(numberSpec($option['series'][0]['label']['formatter'])['style'])->toBe('percent')
        ->and($option['series'][1])->not->toHaveKey('label')
        ->and(numberSpec($option['series'][2]['label']['formatter'])['compact'])->toBeTrue();
});

it('lets the option set the default format, and a Label carry one', function (): void {
    $option = Option::cartesian()
        ->numberFormat(NumberFormat::currency('EUR')->locale('de'))
        ->xAxis(CategoryAxis::make()->data(['a']))
        ->series(LineSeries::make()->data([1])->label(Label::make()->show()->format(NumberFormat::percent(fraction: false))))
        ->toArray();

    expect(numberSpec($option['yAxis']['axisLabel']['formatter']))->toMatchArray(['currency' => 'EUR', 'locale' => 'de'])
        ->and(numberSpec($option['tooltip']['valueFormatter'])['currency'])->toBe('EUR')
        ->and(numberSpec($option['series'][0]['label']['formatter'])['style'])->toBe('percent');
});

it('follows the application locale on every build, so a re-render in another locale is consistent', function (): void {
    $build = fn (): array => Option::cartesian()->xAxis(CategoryAxis::make()->data(['a']))->series(LineSeries::make()->data([1]))->toArray();

    app()->setLocale('pl');
    $polish = $build();
    app()->setLocale('en');
    $english = $build();

    expect($polish['yAxis']['axisLabel']['formatter']['__number__']['locale'])->toBe('pl')
        ->and($english['yAxis']['axisLabel']['formatter']['__number__']['locale'])->toBe('en')
        ->and(json_encode($polish))->not->toBe(json_encode($english));
});
