<?php

use Happenv\FilamentEnhancedCharts\Columns\EnhancedChartColumn;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\LineSeries;

it('resolves a per-record Option from ->chart()', function () {
    $column = EnhancedChartColumn::make('trend')->chart(
        fn (array $record): Option => Option::make()->series(
            LineSeries::make()->data($record['values'])
        )
    );

    $array = $column->resolveOption(['values' => [1, 2, 3]])->toArray();

    expect($array['series'][0]['type'])->toBe('line')
        ->and($array['series'][0]['data'])->toBe([1, 2, 3]);
});

it('defaults to the svg renderer and 120x32, overridable', function () {
    $column = EnhancedChartColumn::make('t');
    expect($column->getRenderer())->toBe('svg')
        ->and($column->getChartWidth())->toBe(120)
        ->and($column->getChartHeight())->toBe(32);

    $column->renderer('canvas')->width(200)->height(60);
    expect($column->getRenderer())->toBe('canvas')
        ->and($column->getChartWidth())->toBe(200)
        ->and($column->getChartHeight())->toBe(60);
});

it('resolves to null when there is no chart resolver', function () {
    expect(EnhancedChartColumn::make('t')->resolveOption(['x' => 1]))->toBeNull();
});

// NOTE: no top-level helper function — Pest loads every test file into one
// global scope, so a shared `function` here risks a fatal redeclare against
// another module. Inline `->resolveOption(...)->toArray()` in each test.

it('sparkline builds a hidden-axis line with a body-appended tooltip', function () {
    $array = EnhancedChartColumn::make('s')->sparkline(fn (array $r) => $r['d'])
        ->resolveOption(['d' => [3, 1, 4, 1, 5]])->toArray();

    expect($array['series'][0]['type'])->toBe('line')
        ->and($array['series'][0]['data'])->toBe([3, 1, 4, 1, 5])
        ->and($array['series'][0]['symbol'])->toBe('none')
        ->and($array['tooltip']['appendTo'])->toBe('body')
        ->and($array['tooltip']['confine'])->toBeFalse()
        ->and($array['xAxis']['show'])->toBeFalse()
        ->and($array['yAxis']['show'])->toBeFalse()
        ->and($array['legend']['show'])->toBeFalse();
});

it('sparkline ->fill() adds an areaStyle and ->bars() switches to a bar series', function () {
    $filled = EnhancedChartColumn::make('s')->sparkline(fn ($r) => $r['d'])->fill()
        ->resolveOption(['d' => [1, 2]])->toArray();
    expect($filled['series'][0]['type'])->toBe('line')
        ->and($filled['series'][0])->toHaveKey('areaStyle');

    $bars = EnhancedChartColumn::make('s')->sparkline(fn ($r) => $r['d'])->bars()
        ->resolveOption(['d' => [1, 2]])->toArray();
    expect($bars['series'][0]['type'])->toBe('bar');
});

it('candles builds a candlestick with hidden axes', function () {
    $array = EnhancedChartColumn::make('c')->candles(fn ($r) => $r['ohlc'])
        ->resolveOption(['ohlc' => [[20, 34, 10, 38], [40, 35, 30, 50]]])->toArray();

    expect($array['series'][0]['type'])->toBe('candlestick')
        ->and($array['series'][0]['data'])->toBe([[20, 34, 10, 38], [40, 35, 30, 50]])
        ->and($array['tooltip']['appendTo'])->toBe('body');
});

it('pie builds a donut, defaults to 40x40, and normalizes label=>value data', function () {
    $column = EnhancedChartColumn::make('p')->pie(fn ($r) => $r['b']);
    $array = $column->resolveOption(['b' => ['A' => 3, 'B' => 5]])->toArray();

    expect($array['series'][0]['type'])->toBe('pie')
        ->and($array['series'][0]['data'])->toBe([['name' => 'A', 'value' => 3], ['name' => 'B', 'value' => 5]])
        ->and($array['series'][0]['label']['show'])->toBeFalse()
        ->and($column->getChartWidth())->toBe(40)
        ->and($column->getChartHeight())->toBe(40);

    // explicit dims win over the pie default
    expect(EnhancedChartColumn::make('p')->pie(fn ($r) => $r['b'])->width(64)->getChartWidth())->toBe(64);
});

it('a preset returns null for blank data (placeholder path)', function () {
    expect(EnhancedChartColumn::make('s')->sparkline(fn ($r) => $r['d'])->resolveOption(['d' => []]))->toBeNull();
});
