<?php

use BcMath\Number;
use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Enums\Orient;
use Happenv\FilamentEnhancedCharts\Option\Component\SingleAxis;

covers(SingleAxis::class);

it('builds a single axis with a type', function () {
    expect(SingleAxis::make()->type('time')->toArray())
        ->toBe(['type' => 'time']);
});

it('normalizes BcMath min/max values', function () {
    expect(SingleAxis::make()->min(new Number('0.50'))->max(10)->toArray())
        ->toBe(['min' => ['__js__' => '0.5'], 'max' => 10]);
});

it('applies width and height', function () {
    expect(SingleAxis::make()->width('80%')->height(60)->toArray())
        ->toBe(['width' => '80%', 'height' => 60]);
});

it('applies left/right/top/bottom layout via HasLayout', function () {
    expect(SingleAxis::make()->left('5%')->right(10)->top(20)->bottom('5%')->toArray())
        ->toBe(['left' => '5%', 'right' => 10, 'top' => 20, 'bottom' => '5%']);
});

it('sets boundaryGap', function () {
    expect(SingleAxis::make()->boundaryGap(['20%', '20%'])->toArray())
        ->toBe(['boundaryGap' => ['20%', '20%']]);
});

it('defaults boundaryGap() to true', function () {
    expect(SingleAxis::make()->boundaryGap()->toArray())->toEqual(['boundaryGap' => true]);
});

it('builds category data', function () {
    expect(SingleAxis::make()->data(['Mon', 'Tue', 'Wed'])->toArray())
        ->toEqual(['data' => ['Mon', 'Tue', 'Wed']]);
});

it('applies splitLine as a show flag or a config array', function () {
    expect(SingleAxis::make()->splitLine(false)->toArray())
        ->toEqual(['splitLine' => ['show' => false]]);

    expect(SingleAxis::make()->splitLine()->toArray())
        ->toEqual(['splitLine' => ['show' => true]]);
});

it('builds interval, accepting a RawJs marker', function () {
    expect(SingleAxis::make()->interval(5)->toArray())->toEqual(['interval' => 5]);

    expect(SingleAxis::make()->interval(RawJs::make('(v)=>v'))->toArray())
        ->toEqual(['interval' => ['__js__' => '(v)=>v']]);
});

it('wraps a RawJs axisLabel formatter in a js marker and merges extra config', function () {
    expect(SingleAxis::make()->axisLabel(RawJs::make('(v)=>v'))->toArray())
        ->toEqual(['axisLabel' => ['formatter' => ['__js__' => '(v)=>v']]]);

    expect(SingleAxis::make()->axisLabel(['rotate' => 45])->toArray())
        ->toEqual(['axisLabel' => ['rotate' => 45]]);
});

it('builds show, z, zlevel, and silent (shared HasAxisDecorations)', function () {
    expect(SingleAxis::make()->show()->z(1)->zlevel(2)->silent()->toArray())
        ->toEqual(['show' => true, 'z' => 1, 'zlevel' => 2, 'silent' => true]);
});

it('applies axisLine, axisTick, splitArea, minorTick, and minorSplitLine as show flags or config arrays', function () {
    expect(SingleAxis::make()->axisLine()->toArray())->toEqual(['axisLine' => ['show' => true]]);
    expect(SingleAxis::make()->axisTick(false)->toArray())->toEqual(['axisTick' => ['show' => false]]);
    expect(SingleAxis::make()->splitArea()->toArray())->toEqual(['splitArea' => ['show' => true]]);
    expect(SingleAxis::make()->minorTick()->toArray())->toEqual(['minorTick' => ['show' => true]]);
    expect(SingleAxis::make()->minorSplitLine(false)->toArray())->toEqual(['minorSplitLine' => ['show' => false]]);
});

it('builds minInterval, maxInterval, and splitNumber', function () {
    expect(SingleAxis::make()->minInterval(1)->maxInterval(10)->splitNumber(6)->toArray())
        ->toEqual(['minInterval' => 1, 'maxInterval' => 10, 'splitNumber' => 6]);
});

it('positions the axis name via nameGap, nameLocation, nameTextStyle, and nameRotate', function () {
    expect(SingleAxis::make()->nameGap(10)->nameLocation('start')->nameTextStyle(['color' => '#333'])->nameRotate(90)->toArray())
        ->toEqual([
            'nameGap' => 10,
            'nameLocation' => 'start',
            'nameTextStyle' => ['color' => '#333'],
            'nameRotate' => 90,
        ]);
});

it('applies axisPointer as a show flag or a config array', function () {
    expect(SingleAxis::make()->axisPointer(false)->toArray())->toEqual(['axisPointer' => ['show' => false]]);
    expect(SingleAxis::make()->axisPointer(['type' => 'line'])->toArray())
        ->toEqual(['axisPointer' => ['type' => 'line']]);
});

it('sets scale', function () {
    expect(SingleAxis::make()->scale()->toArray())->toEqual(['scale' => true]);
});

it('accepts an Orient enum or a raw string on orient()', function () {
    expect(SingleAxis::make()->orient(Orient::Vertical)->toArray())
        ->toBe(['orient' => 'vertical']);

    expect(SingleAxis::make()->orient('horizontal')->toArray())
        ->toBe(['orient' => 'horizontal']);
});

it('sets inverse, defaulting to true', function () {
    expect(SingleAxis::make()->inverse()->toArray())->toBe(['inverse' => true]);
    expect(SingleAxis::make()->inverse(false)->toArray())->toBe(['inverse' => false]);
});

it('lets raw() override a single axis type', function () {
    expect(SingleAxis::make()->type('value')->raw(['type' => 'category'])->toArray()['type'])
        ->toBe('category');
});
