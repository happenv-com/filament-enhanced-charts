<?php

declare(strict_types=1);

use BcMath\Number;
use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Enums\AxisType;
use Happenv\FilamentEnhancedCharts\Option\Component\AngleAxis;

covers(AngleAxis::class);

it('accepts an AxisType enum or a raw string on type()', function (): void {
    expect(AngleAxis::make()->type(AxisType::Category)->toArray())
        ->toEqual(['type' => 'category']);

    expect(AngleAxis::make()->type('value')->toArray())
        ->toEqual(['type' => 'value']);
});

it('builds startAngle and clockwise', function (): void {
    expect(AngleAxis::make()->startAngle(90)->clockwise(false)->toArray())
        ->toEqual(['startAngle' => 90, 'clockwise' => false]);
});

it('defaults clockwise() to true', function (): void {
    expect(AngleAxis::make()->clockwise()->toArray())->toEqual(['clockwise' => true]);
});

it('normalizes a BcMath min and passes a plain max through', function (): void {
    expect(AngleAxis::make()->min(new Number('0.50'))->max(100)->toArray())
        ->toEqual([
            'min' => ['__js__' => '0.5'],
            'max' => 100,
        ]);
});

it('accepts boundaryGap as a bool or a pair', function (): void {
    expect(AngleAxis::make()->boundaryGap(true)->toArray())
        ->toEqual(['boundaryGap' => true]);

    expect(AngleAxis::make()->boundaryGap(['20%', '20%'])->toArray())
        ->toEqual(['boundaryGap' => ['20%', '20%']]);
});

it('builds category data', function (): void {
    expect(AngleAxis::make()->data(['Mon', 'Tue', 'Wed'])->toArray())
        ->toEqual(['data' => ['Mon', 'Tue', 'Wed']]);
});

it('normalizes a BcMath value inside data', function (): void {
    expect(AngleAxis::make()->data([new Number('1.50'), 2])->toArray())
        ->toEqual(['data' => [['__js__' => '1.5'], 2]]);
});

it('builds interval', function (): void {
    expect(AngleAxis::make()->interval(5)->toArray())->toEqual(['interval' => 5]);
});

it('applies splitLine as a show flag or a config array', function (): void {
    expect(AngleAxis::make()->splitLine(false)->toArray())
        ->toEqual(['splitLine' => ['show' => false]]);

    expect(AngleAxis::make()->splitLine()->toArray())
        ->toEqual(['splitLine' => ['show' => true]]);

    expect(AngleAxis::make()->splitLine(['show' => true, 'interval' => 2])->toArray())
        ->toEqual(['splitLine' => ['show' => true, 'interval' => 2]]);
});

it('wraps a RawJs axisLabel formatter in a js marker', function (): void {
    expect(AngleAxis::make()->axisLabel(RawJs::make('(v)=>v'))->toArray())
        ->toEqual(['axisLabel' => ['formatter' => ['__js__' => '(v)=>v']]]);
});

it('treats a bare string axisLabel as a literal ECharts template', function (): void {
    expect(AngleAxis::make()->axisLabel('{value}')->toArray())
        ->toEqual(['axisLabel' => ['formatter' => '{value}']]);
});

it('passes an axisLabel array through as a full config', function (): void {
    expect(AngleAxis::make()->axisLabel(['show' => false, 'rotate' => 45])->toArray())
        ->toEqual(['axisLabel' => ['show' => false, 'rotate' => 45]]);
});

it('builds polarIndex', function (): void {
    expect(AngleAxis::make()->polarIndex(1)->toArray())->toEqual(['polarIndex' => 1]);
});

it('builds endAngle', function (): void {
    expect(AngleAxis::make()->endAngle(270)->toArray())->toEqual(['endAngle' => 270]);
});

it('combines startAngle, endAngle, and clockwise', function (): void {
    expect(AngleAxis::make()->startAngle(90)->endAngle(-270)->clockwise(false)->toArray())
        ->toEqual(['startAngle' => 90, 'endAngle' => -270, 'clockwise' => false]);
});

it('accepts a RawJs interval marker in addition to a plain number', function (): void {
    expect(AngleAxis::make()->interval(RawJs::make('(v)=>v'))->toArray())
        ->toEqual(['interval' => ['__js__' => '(v)=>v']]);
});

it('defaults boundaryGap() to true', function (): void {
    expect(AngleAxis::make()->boundaryGap()->toArray())->toEqual(['boundaryGap' => true]);
});

it('builds show, z, zlevel, and silent (shared HasAxisDecorations)', function (): void {
    expect(AngleAxis::make()->show(false)->z(3)->zlevel(1)->silent()->toArray())
        ->toEqual(['show' => false, 'z' => 3, 'zlevel' => 1, 'silent' => true]);
});

it('applies axisLine, axisTick, splitArea, minorTick, and minorSplitLine as show flags or config arrays', function (): void {
    expect(AngleAxis::make()->axisLine(false)->toArray())->toEqual(['axisLine' => ['show' => false]]);
    expect(AngleAxis::make()->axisTick()->toArray())->toEqual(['axisTick' => ['show' => true]]);
    expect(AngleAxis::make()->splitArea()->toArray())->toEqual(['splitArea' => ['show' => true]]);
    expect(AngleAxis::make()->minorTick(['splitNumber' => 5])->toArray())
        ->toEqual(['minorTick' => ['splitNumber' => 5]]);
    expect(AngleAxis::make()->minorSplitLine()->toArray())->toEqual(['minorSplitLine' => ['show' => true]]);
});

it('builds minInterval, maxInterval, and splitNumber', function (): void {
    expect(AngleAxis::make()->minInterval(1)->maxInterval(10)->splitNumber(5)->toArray())
        ->toEqual(['minInterval' => 1, 'maxInterval' => 10, 'splitNumber' => 5]);
});

it('positions the axis name via nameGap, nameLocation, nameTextStyle, and nameRotate', function (): void {
    expect(AngleAxis::make()->nameGap(20)->nameLocation('middle')->nameTextStyle(['color' => '#333'])->nameRotate(45)->toArray())
        ->toEqual([
            'nameGap' => 20,
            'nameLocation' => 'middle',
            'nameTextStyle' => ['color' => '#333'],
            'nameRotate' => 45,
        ]);
});

it('applies axisPointer as a show flag or a config array', function (): void {
    expect(AngleAxis::make()->axisPointer(false)->toArray())->toEqual(['axisPointer' => ['show' => false]]);
    expect(AngleAxis::make()->axisPointer(['type' => 'cross'])->toArray())
        ->toEqual(['axisPointer' => ['type' => 'cross']]);
});

it('sets scale', function (): void {
    expect(AngleAxis::make()->scale()->toArray())->toEqual(['scale' => true]);
});

it('lets raw() override a typed key', function (): void {
    expect(AngleAxis::make()->type('value')->raw(['type' => 'category'])->toArray())
        ->toEqual(['type' => 'category']);
});
