<?php

use BcMath\Number;
use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Enums\AxisPointerType;
use Happenv\FilamentEnhancedCharts\Option\Axis\CategoryAxis;
use Happenv\FilamentEnhancedCharts\Option\Axis\LogAxis;
use Happenv\FilamentEnhancedCharts\Option\Axis\ValueAxis;
use Happenv\FilamentEnhancedCharts\Option\Component\AxisPointer;

it('builds a category axis with data', function () {
    expect(CategoryAxis::make()->data(['Jan', 'Feb'])->toArray())
        ->toBe(['type' => 'category', 'data' => ['Jan', 'Feb']]);
});

it('builds a value axis with a name and js formatter marker', function () {
    expect(ValueAxis::make()->name('USD')->axisLabel(RawJs::make('(v)=>v'))->toArray())
        ->toBe([
            'type' => 'value',
            'name' => 'USD',
            'axisLabel' => ['formatter' => ['__js__' => '(v)=>v']],
        ]);
});

it('treats a bare string formatter as a literal ECharts template', function () {
    expect(ValueAxis::make()->axisLabel('{value}')->toArray())
        ->toBe(['type' => 'value', 'axisLabel' => ['formatter' => '{value}']]);
});

it('passes a template with extra text through unchanged', function () {
    expect(ValueAxis::make()->axisLabel('{value} °C')->toArray())
        ->toBe(['type' => 'value', 'axisLabel' => ['formatter' => '{value} °C']]);
});

it('normalizes min through BcMath and passes a plain int max through', function () {
    expect(ValueAxis::make()->min(new Number('0.50'))->max(100)->toArray())
        ->toBe([
            'type' => 'value',
            'min' => ['__js__' => '0.5'],
            'max' => 100,
        ]);
});

it('applies splitLine as a show flag', function () {
    expect(ValueAxis::make()->splitLine(false)->toArray())
        ->toBe(['type' => 'value', 'splitLine' => ['show' => false]]);
});

it('defaults ->splitLine() to true', function () {
    expect(ValueAxis::make()->splitLine()->toArray())
        ->toBe(['type' => 'value', 'splitLine' => ['show' => true]]);
});

it('builds a minimal log axis', function () {
    expect(LogAxis::make()->toArray())
        ->toBe(['type' => 'log']);
});

it('positions the axis name via nameGap and nameLocation', function () {
    expect(ValueAxis::make()->name('USD')->nameGap(20)->nameLocation('middle')->toArray())
        ->toEqual([
            'type' => 'value',
            'name' => 'USD',
            'nameGap' => 20,
            'nameLocation' => 'middle',
        ]);
});

it('places the axis via position and offset', function () {
    expect(ValueAxis::make()->position('right')->offset(10)->toArray())
        ->toEqual([
            'type' => 'value',
            'position' => 'right',
            'offset' => 10,
        ]);
});

it('applies splitArea as a show flag and as a raw config array', function () {
    expect(ValueAxis::make()->splitArea()->toArray())
        ->toEqual(['type' => 'value', 'splitArea' => ['show' => true]]);

    expect(ValueAxis::make()->splitArea(['areaStyle' => ['color' => ['#eee', '#fff']]])->toArray())
        ->toEqual([
            'type' => 'value',
            'splitArea' => ['areaStyle' => ['color' => ['#eee', '#fff']]],
        ]);
});

it('accepts a splitLine config array in addition to a bool', function () {
    expect(ValueAxis::make()->splitLine(['lineStyle' => ['type' => 'dashed']])->toArray())
        ->toEqual([
            'type' => 'value',
            'splitLine' => ['lineStyle' => ['type' => 'dashed']],
        ]);
});

it('attaches an axis pointer built from the AxisPointer node', function () {
    expect(
        ValueAxis::make()
            ->axisPointer(AxisPointer::make()->type(AxisPointerType::Cross)->snap())
            ->toArray()
    )->toEqual([
        'type' => 'value',
        'axisPointer' => ['type' => 'cross', 'snap' => true],
    ]);
});

it('applies axisPointer as a show flag', function () {
    expect(ValueAxis::make()->axisPointer(false)->toArray())
        ->toEqual(['type' => 'value', 'axisPointer' => ['show' => false]]);
});
