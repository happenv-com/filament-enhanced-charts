<?php

use BcMath\Number;
use Happenv\FilamentEnhancedCharts\Option\Axis\CategoryAxis;
use Happenv\FilamentEnhancedCharts\Option\Axis\ValueAxis;
use Happenv\FilamentEnhancedCharts\Option\Component\Legend;
use Happenv\FilamentEnhancedCharts\Option\Component\VisualMap;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\BarSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\LineSeries;

it('composes a full cartesian option with normalized data', function () {
    $option = Option::make()
        ->color('#0084d1', '#d1d5db')
        ->legend(Legend::make()->top(0))
        ->xAxis(CategoryAxis::make()->data(['Jan', 'Feb']))
        ->yAxis(ValueAxis::make())
        ->series(LineSeries::make()->name('Sale')->data([7, new Number('4.50')]))
        ->raw(['grid' => ['top' => 8]]);

    expect($option->toArray())->toBe([
        'color' => ['#0084d1', '#d1d5db'],
        'legend' => ['top' => 0],
        'xAxis' => ['type' => 'category', 'data' => ['Jan', 'Feb']],
        'yAxis' => ['type' => 'value'],
        'series' => [
            ['type' => 'line', 'name' => 'Sale', 'data' => [7, ['__js__' => '4.5']]],
        ],
        'grid' => ['top' => 8],
    ]);
});

it('lets raw() override a typed key', function () {
    expect(Option::make()->color('#000')->raw(['color' => ['#fff']])->toArray())
        ->toBe(['color' => ['#fff']]);
});

it('emits a single legend as an object and multiple legends as a list', function () {
    expect(Option::make()->legend(Legend::make()->top(0))->toArray())
        ->toBe(['legend' => ['top' => 0]]);

    expect(Option::make()->legend(Legend::make(), Legend::make()->right(0))->toArray())
        ->toEqual(['legend' => [[], ['right' => 0]]]);
});

it('emits a single visualMap as an object', function () {
    expect(Option::make()->visualMap(VisualMap::piecewise())->toArray())
        ->toBe(['visualMap' => ['type' => 'piecewise']]);
});

it('emits multiple visualMap entries as a list', function () {
    expect(Option::make()->visualMap(VisualMap::piecewise(), VisualMap::continuous())->toArray())
        ->toBe(['visualMap' => [['type' => 'piecewise'], ['type' => 'continuous']]]);
});

it('emits multiple y-axes as a list', function () {
    expect(
        Option::make()
            ->xAxis(CategoryAxis::make()->data(['a']))
            ->yAxis(ValueAxis::make(), ValueAxis::make()->name('%'))
            ->toArray()['yAxis']
    )->toBe([
        ['type' => 'value'],
        ['type' => 'value', 'name' => '%'],
    ]);
});

it('emits multiple x-axes as a list', function () {
    expect(
        Option::make()
            ->xAxis(CategoryAxis::make()->data(['a']), CategoryAxis::make()->data(['b']))
            ->toArray()['xAxis']
    )->toBe([
        ['type' => 'category', 'data' => ['a']],
        ['type' => 'category', 'data' => ['b']],
    ]);
});

it('defaults the y-axis to value when an x-axis is set but no y-axis is', function () {
    expect(Option::make()->xAxis(CategoryAxis::make()->data(['Jan']))->series(LineSeries::make()->data([1]))->toArray())
        ->toBe([
            'xAxis' => ['type' => 'category', 'data' => ['Jan']],
            'yAxis' => ['type' => 'value'],
            'series' => [['type' => 'line', 'data' => [1]]],
        ]);
});

it('defaults the x-axis to value when a y-axis is set but no x-axis is', function () {
    // Mirror of the y-default, for a horizontal (category-y) chart.
    expect(Option::make()->yAxis(CategoryAxis::make()->data(['Jan']))->series(BarSeries::make()->data([1]))->toArray())
        ->toBe([
            'xAxis' => ['type' => 'value'],
            'yAxis' => ['type' => 'category', 'data' => ['Jan']],
            'series' => [['type' => 'bar', 'data' => [1]]],
        ]);
});

it('does not default any axis when neither is set', function () {
    expect(Option::make()->series(LineSeries::make()->data([1]))->toArray())
        ->toBe(['series' => [['type' => 'line', 'data' => [1]]]]);
});

it('keeps an explicit y-axis instead of the default', function () {
    expect(Option::make()->xAxis(CategoryAxis::make()->data(['a']))->yAxis(CategoryAxis::make()->data(['x', 'y']))->toArray())
        ->toBe([
            'xAxis' => ['type' => 'category', 'data' => ['a']],
            'yAxis' => ['type' => 'category', 'data' => ['x', 'y']],
        ]);
});

it('cartesian() presets an axis tooltip and a top legend', function () {
    expect(Option::cartesian()->xAxis(CategoryAxis::make()->data(['a']))->series(LineSeries::make()->data([1]))->toArray())
        ->toBe([
            'legend' => ['top' => 0],
            'tooltip' => ['trigger' => 'axis'],
            'xAxis' => ['type' => 'category', 'data' => ['a']],
            'yAxis' => ['type' => 'value'],
            'series' => [['type' => 'line', 'data' => [1]]],
        ]);
});

it('resolves a Filament color palette in the option palette', function () {
    expect(Option::make()->color([500 => 'base'], '#000')->toArray())
        ->toBe(['color' => ['base', '#000']]);
});
