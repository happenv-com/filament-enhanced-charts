<?php

use BcMath\Number;
use Happenv\FilamentEnhancedCharts\Enums\Symbol;
use Happenv\FilamentEnhancedCharts\Option\DataPoint;
use Happenv\FilamentEnhancedCharts\Option\Series\LineSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\AreaStyle;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;
use Happenv\FilamentEnhancedCharts\Option\Style\LineStyle;

it('builds a minimal line series with normalized BcMath data', function () {
    expect(LineSeries::make()->name('Sale')->data([7, new Number('4.50')])->toArray())
        ->toBe(['type' => 'line', 'name' => 'Sale', 'data' => [7, ['__js__' => '4.5']]]);
});

it('applies smooth, dashed and stack', function () {
    expect(LineSeries::make()->smooth()->dashed()->stack('a')->data([1])->toArray())
        ->toBe([
            'type' => 'line',
            'data' => [1],
            'smooth' => true,
            'lineStyle' => ['type' => 'dashed'],
            'stack' => 'a',
        ]);
});

it('merges dashed() into an existing lineStyle instead of clobbering it', function () {
    expect(
        LineSeries::make()->lineStyle(LineStyle::make()->color('#f00'))->dashed()->data([1])->toArray()['lineStyle']
    )->toBe(['color' => '#f00', 'type' => 'dashed']);
});

it('keeps the dash regardless of dashed()/lineStyle() call order', function () {
    // Regression: dashed() before lineStyle() used to be clobbered by the
    // lineStyle() replace. dashed() is now an order-independent modifier.
    $dashedFirst = LineSeries::make()->dashed()->lineStyle(LineStyle::make()->width(2))->data([1])->toArray()['lineStyle'];
    $dashedLast = LineSeries::make()->lineStyle(LineStyle::make()->width(2))->dashed()->data([1])->toArray()['lineStyle'];

    expect($dashedFirst)->toBe(['width' => 2, 'type' => 'dashed'])
        ->and($dashedLast)->toBe(['width' => 2, 'type' => 'dashed']);
});

it('accepts a float smoothness value for smooth', function () {
    expect(LineSeries::make()->smooth(0.5)->data([1])->toArray())
        ->toEqual(['type' => 'line', 'data' => [1], 'smooth' => 0.5]);
});

it('emits smooth => false when explicitly disabled', function () {
    expect(LineSeries::make()->smooth(false)->data([1])->toArray())
        ->toEqual(['type' => 'line', 'data' => [1], 'smooth' => false]);
});

it('applies step as a boolean or a named position', function () {
    expect(LineSeries::make()->step(true)->data([1])->toArray())
        ->toEqual(['type' => 'line', 'data' => [1], 'step' => true]);

    expect(LineSeries::make()->step('middle')->data([1])->toArray())
        ->toEqual(['type' => 'line', 'data' => [1], 'step' => 'middle']);
});

it('applies showAllSymbol on a line series', function () {
    expect(LineSeries::make()->showAllSymbol()->data([1])->toArray())
        ->toEqual(['type' => 'line', 'data' => [1], 'showAllSymbol' => true]);
});

it('lets raw() override any typed key', function () {
    expect(LineSeries::make()->smooth()->raw(['smooth' => false, 'z' => 3])->toArray())
        ->toBe(['type' => 'line', 'smooth' => false, 'z' => 3]);
});

it('builds a DataPoint with normalized value, name and itemStyle', function () {
    expect(DataPoint::make(new Number('5.00'))->name('A')->toArray())
        ->toBe(['value' => ['__js__' => '5'], 'name' => 'A']);
});

it('serializes an empty areaStyle as a JS object, not an array', function () {
    expect(json_encode(LineSeries::make()->area()->data([1])->toArray()))
        ->toContain('"areaStyle":{}');
});

it('applies an AreaStyle builder to areaStyle', function () {
    expect(LineSeries::make()->area(AreaStyle::make()->opacity(0.3))->data([1])->toArray()['areaStyle'])
        ->toBe(['opacity' => 0.3]);
});

it('emits no areaStyle when area is false', function () {
    expect(LineSeries::make()->area(false)->data([1])->toArray())
        ->toBe(['type' => 'line', 'data' => [1]]);
});

it('accepts areaStyle as the ECharts-named equivalent of area', function () {
    expect(LineSeries::make()->areaStyle(['opacity' => 0.3])->data([1])->toArray()['areaStyle'])
        ->toBe(['opacity' => 0.3]);
});

it('binds a series to a secondary y-axis by index', function () {
    expect(LineSeries::make()->yAxisIndex(1)->data([1])->toArray())
        ->toHaveKey('yAxisIndex', 1);
});

it('binds a series to a secondary x-axis by index', function () {
    expect(LineSeries::make()->xAxisIndex(1)->data([1])->toArray())
        ->toHaveKey('xAxisIndex', 1);
});

it('applies symbol with a size', function () {
    expect(LineSeries::make()->symbol('circle')->symbolSize(6)->data([1])->toArray())
        ->toBe(['type' => 'line', 'data' => [1], 'symbol' => 'circle', 'symbolSize' => 6]);
});

it('accepts a Symbol enum and size for the marker', function () {
    expect(LineSeries::make()->symbol(Symbol::Diamond)->symbolSize(8)->data([1])->toArray())
        ->toBe(['type' => 'line', 'data' => [1], 'symbol' => 'diamond', 'symbolSize' => 8]);
});

it('builds a DataPoint with itemStyle and label parity with its siblings', function () {
    expect(
        DataPoint::make(5)->itemStyle(ItemStyle::make()->color('#f00'))->label(Label::make()->show())->toArray()
    )->toBe([
        'value' => 5,
        'itemStyle' => ['color' => '#f00'],
        'label' => ['show' => true],
    ]);
});
