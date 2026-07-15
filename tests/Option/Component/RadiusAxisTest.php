<?php

use BcMath\Number;
use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Enums\AxisType;
use Happenv\FilamentEnhancedCharts\Option\Component\RadiusAxis;

covers(RadiusAxis::class);

it('accepts an AxisType enum or a raw string on type()', function () {
    expect(RadiusAxis::make()->type(AxisType::Value)->toArray())
        ->toEqual(['type' => 'value']);

    expect(RadiusAxis::make()->type('category')->toArray())
        ->toEqual(['type' => 'category']);
});

it('builds a name', function () {
    expect(RadiusAxis::make()->name('Score')->toArray())->toEqual(['name' => 'Score']);
});

it('normalizes a BcMath min and passes a plain max through', function () {
    expect(RadiusAxis::make()->min(new Number('0.50'))->max(100)->toArray())
        ->toEqual([
            'min' => ['__js__' => '0.5'],
            'max' => 100,
        ]);
});

it('builds category data', function () {
    expect(RadiusAxis::make()->data(['A', 'B'])->toArray())
        ->toEqual(['data' => ['A', 'B']]);
});

it('normalizes a BcMath value inside data', function () {
    expect(RadiusAxis::make()->data([new Number('1.50'), 2])->toArray())
        ->toEqual(['data' => [['__js__' => '1.5'], 2]]);
});

it('wraps a RawJs axisLabel formatter in a js marker', function () {
    expect(RadiusAxis::make()->axisLabel(RawJs::make('(v)=>v'))->toArray())
        ->toEqual(['axisLabel' => ['formatter' => ['__js__' => '(v)=>v']]]);
});

it('treats a bare string axisLabel as a literal ECharts template', function () {
    expect(RadiusAxis::make()->axisLabel('{value}')->toArray())
        ->toEqual(['axisLabel' => ['formatter' => '{value}']]);
});

it('passes an axisLabel array through as a full config', function () {
    expect(RadiusAxis::make()->axisLabel(['show' => false])->toArray())
        ->toEqual(['axisLabel' => ['show' => false]]);
});

it('applies splitLine as a show flag or a config array', function () {
    expect(RadiusAxis::make()->splitLine(false)->toArray())
        ->toEqual(['splitLine' => ['show' => false]]);

    expect(RadiusAxis::make()->splitLine()->toArray())
        ->toEqual(['splitLine' => ['show' => true]]);

    expect(RadiusAxis::make()->splitLine(['show' => true, 'interval' => 2])->toArray())
        ->toEqual(['splitLine' => ['show' => true, 'interval' => 2]]);
});

it('builds polarIndex', function () {
    expect(RadiusAxis::make()->polarIndex(0)->toArray())->toEqual(['polarIndex' => 0]);
});

it('accepts boundaryGap as a bool or a pair, defaulting to true', function () {
    expect(RadiusAxis::make()->boundaryGap()->toArray())->toEqual(['boundaryGap' => true]);

    expect(RadiusAxis::make()->boundaryGap(['20%', '20%'])->toArray())
        ->toEqual(['boundaryGap' => ['20%', '20%']]);
});

it('builds interval, accepting a RawJs marker', function () {
    expect(RadiusAxis::make()->interval(5)->toArray())->toEqual(['interval' => 5]);

    expect(RadiusAxis::make()->interval(RawJs::make('(v)=>v'))->toArray())
        ->toEqual(['interval' => ['__js__' => '(v)=>v']]);
});

it('builds show, z, zlevel, and silent (shared HasAxisDecorations)', function () {
    expect(RadiusAxis::make()->show()->z(2)->zlevel(0)->silent(false)->toArray())
        ->toEqual(['show' => true, 'z' => 2, 'zlevel' => 0, 'silent' => false]);
});

it('applies axisLine, axisTick, splitArea, minorTick, and minorSplitLine as show flags or config arrays', function () {
    expect(RadiusAxis::make()->axisLine()->toArray())->toEqual(['axisLine' => ['show' => true]]);
    expect(RadiusAxis::make()->axisTick(false)->toArray())->toEqual(['axisTick' => ['show' => false]]);
    expect(RadiusAxis::make()->splitArea()->toArray())->toEqual(['splitArea' => ['show' => true]]);
    expect(RadiusAxis::make()->minorTick()->toArray())->toEqual(['minorTick' => ['show' => true]]);
    expect(RadiusAxis::make()->minorSplitLine(['lineStyle' => ['type' => 'dashed']])->toArray())
        ->toEqual(['minorSplitLine' => ['lineStyle' => ['type' => 'dashed']]]);
});

it('builds minInterval, maxInterval, and splitNumber', function () {
    expect(RadiusAxis::make()->minInterval(1)->maxInterval(10)->splitNumber(4)->toArray())
        ->toEqual(['minInterval' => 1, 'maxInterval' => 10, 'splitNumber' => 4]);
});

it('positions the axis name via nameGap, nameLocation, nameTextStyle, and nameRotate', function () {
    expect(RadiusAxis::make()->nameGap(15)->nameLocation('end')->nameTextStyle(['fontSize' => 10])->nameRotate(30)->toArray())
        ->toEqual([
            'nameGap' => 15,
            'nameLocation' => 'end',
            'nameTextStyle' => ['fontSize' => 10],
            'nameRotate' => 30,
        ]);
});

it('applies axisPointer as a show flag or a config array', function () {
    expect(RadiusAxis::make()->axisPointer()->toArray())->toEqual(['axisPointer' => ['show' => true]]);
    expect(RadiusAxis::make()->axisPointer(['type' => 'shadow'])->toArray())
        ->toEqual(['axisPointer' => ['type' => 'shadow']]);
});

it('sets scale', function () {
    expect(RadiusAxis::make()->scale(false)->toArray())->toEqual(['scale' => false]);
});

it('lets raw() override a typed key', function () {
    expect(RadiusAxis::make()->type('value')->raw(['type' => 'category'])->toArray())
        ->toEqual(['type' => 'category']);
});
