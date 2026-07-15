<?php

use BcMath\Number;
use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Enums\AxisType;
use Happenv\FilamentEnhancedCharts\Option\Component\ParallelAxis;

covers(ParallelAxis::class);

it('builds dim and name', function () {
    expect(ParallelAxis::make()->dim(0)->name('Price')->toArray())
        ->toEqual(['dim' => 0, 'name' => 'Price']);
});

it('accepts an AxisType enum or a raw string on type()', function () {
    expect(ParallelAxis::make()->type(AxisType::Category)->toArray())
        ->toEqual(['type' => 'category']);

    expect(ParallelAxis::make()->type('value')->toArray())
        ->toEqual(['type' => 'value']);
});

it('normalizes a BcMath min and passes a plain max through', function () {
    expect(ParallelAxis::make()->min(new Number('0.50'))->max(100)->toArray())
        ->toEqual([
            'min' => ['__js__' => '0.5'],
            'max' => 100,
        ]);
});

it('defaults inverse() to true', function () {
    expect(ParallelAxis::make()->inverse()->toArray())->toEqual(['inverse' => true]);
});

it('builds category data', function () {
    expect(ParallelAxis::make()->data(['A', 'B'])->toArray())
        ->toEqual(['data' => ['A', 'B']]);
});

it('normalizes a BcMath value inside data', function () {
    expect(ParallelAxis::make()->data([new Number('1.50'), 2])->toArray())
        ->toEqual(['data' => [['__js__' => '1.5'], 2]]);
});

it('builds nameLocation', function () {
    expect(ParallelAxis::make()->nameLocation('end')->toArray())
        ->toEqual(['nameLocation' => 'end']);
});

it('wraps a RawJs axisLabel formatter in a js marker', function () {
    expect(ParallelAxis::make()->axisLabel(RawJs::make('(v)=>v'))->toArray())
        ->toEqual(['axisLabel' => ['formatter' => ['__js__' => '(v)=>v']]]);
});

it('treats a bare string axisLabel as a literal ECharts template', function () {
    expect(ParallelAxis::make()->axisLabel('{value}')->toArray())
        ->toEqual(['axisLabel' => ['formatter' => '{value}']]);
});

it('passes an axisLabel array through as a full config', function () {
    expect(ParallelAxis::make()->axisLabel(['show' => false])->toArray())
        ->toEqual(['axisLabel' => ['show' => false]]);
});

it('lets raw() override a typed key', function () {
    expect(ParallelAxis::make()->type('value')->raw(['type' => 'category'])->toArray())
        ->toEqual(['type' => 'category']);
});

it('defaults scale() to true', function () {
    expect(ParallelAxis::make()->scale()->toArray())->toEqual(['scale' => true]);
    expect(ParallelAxis::make()->scale(false)->toArray())->toEqual(['scale' => false]);
});
