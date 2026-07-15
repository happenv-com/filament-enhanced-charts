<?php

use Happenv\FilamentEnhancedCharts\Enums\Symbol;
use Happenv\FilamentEnhancedCharts\Option\Mark\MarkArea;
use Happenv\FilamentEnhancedCharts\Option\Mark\MarkLine;
use Happenv\FilamentEnhancedCharts\Option\Mark\MarkPoint;
use Happenv\FilamentEnhancedCharts\Option\Series\LineSeries;

it('builds a mark line with a stat entry and a named horizontal line', function () {
    expect(MarkLine::make()->average()->at(100, 'target')->toArray())
        ->toBe([
            'data' => [
                ['type' => 'average', 'name' => 'avg'],
                ['yAxis' => 100, 'name' => 'target'],
            ],
        ]);
});

it('applies a mark line to a series', function () {
    expect(LineSeries::make()->markLine(MarkLine::make()->max())->data([1])->toArray()['markLine'])
        ->toBe(['data' => [['type' => 'max', 'name' => 'max']]]);
});

it('builds a mark point with a max stat entry', function () {
    expect(MarkPoint::make()->max()->toArray())
        ->toBe(['data' => [['type' => 'max', 'name' => 'max']]]);
});

it('applies a mark point to a series', function () {
    expect(LineSeries::make()->markPoint(MarkPoint::make()->min())->data([1])->toArray()['markPoint'])
        ->toBe(['data' => [['type' => 'min', 'name' => 'min']]]);
});

it('builds a mark area band as a pair of endpoint descriptors', function () {
    expect(MarkArea::make()->band('Q1', 'Q2')->toArray())
        ->toBe(['data' => [[['xAxis' => 'Q1'], ['xAxis' => 'Q2']]]]);
});

it('applies a mark area to a series', function () {
    expect(LineSeries::make()->markArea(MarkArea::make()->band('Q1', 'Q2', 'holiday'))->data([1])->toArray()['markArea'])
        ->toBe(['data' => [[['xAxis' => 'Q1', 'name' => 'holiday'], ['xAxis' => 'Q2']]]]);
});

it('builds a mark point with a symbol, symbolSize and symbolOffset', function () {
    expect(
        MarkPoint::make()->max()->symbol(Symbol::Pin)->symbolSize(50)->symbolOffset([0, -10])->toArray()
    )->toEqual([
        'data' => [['type' => 'max', 'name' => 'max']],
        'symbol' => 'pin',
        'symbolSize' => 50,
        'symbolOffset' => [0, -10],
    ]);
});

it('accepts a bare string symbol and an array symbolSize on a mark point', function () {
    expect(MarkPoint::make()->symbol('image://logo.png')->symbolSize([20, 30])->toArray())
        ->toEqual(['data' => [], 'symbol' => 'image://logo.png', 'symbolSize' => [20, 30]]);
});

it('builds a mark line with a symbol pair and symbolSize', function () {
    expect(
        MarkLine::make()->average()->symbol([Symbol::None, Symbol::Arrow])->symbolSize(10)->toArray()
    )->toEqual([
        'data' => [['type' => 'average', 'name' => 'avg']],
        'symbol' => ['none', 'arrow'],
        'symbolSize' => 10,
    ]);
});

it('accepts a single Symbol enum and an array symbolSize on a mark line', function () {
    expect(MarkLine::make()->symbol(Symbol::Circle)->symbolSize([5, 5])->toArray())
        ->toEqual(['data' => [], 'symbol' => 'circle', 'symbolSize' => [5, 5]]);
});

it('defaults silent() to true and builds tooltip on a mark line', function () {
    expect(MarkLine::make()->silent()->tooltip(['show' => false])->toArray())
        ->toEqual(['data' => [], 'silent' => true, 'tooltip' => ['show' => false]]);

    expect(MarkLine::make()->silent(false)->toArray())
        ->toEqual(['data' => [], 'silent' => false]);
});

it('defaults silent() to true and builds tooltip on a mark area', function () {
    expect(MarkArea::make()->silent()->tooltip(['show' => false])->toArray())
        ->toEqual(['data' => [], 'silent' => true, 'tooltip' => ['show' => false]]);

    expect(MarkArea::make()->silent(false)->toArray())
        ->toEqual(['data' => [], 'silent' => false]);
});

it('defaults silent() to true and builds tooltip on a mark point', function () {
    expect(MarkPoint::make()->silent()->tooltip(['show' => false])->toArray())
        ->toEqual(['data' => [], 'silent' => true, 'tooltip' => ['show' => false]]);

    expect(MarkPoint::make()->silent(false)->toArray())
        ->toEqual(['data' => [], 'silent' => false]);
});
