<?php

use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Option\Component\VisualMap;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;

covers(VisualMap::class);

it('sets an inRange color scale via colors() and inRange()', function () {
    expect(VisualMap::continuous()->min(0)->max(100)->colors('#eee', '#c00')->toArray())
        ->toEqual(['type' => 'continuous', 'min' => 0, 'max' => 100, 'inRange' => ['color' => ['#eee', '#c00']]]);

    expect(VisualMap::continuous()->inRange(['symbolSize' => [5, 40]])->toArray())
        ->toEqual(['type' => 'continuous', 'inRange' => ['symbolSize' => [5, 40]]]);
});

it('sets outOfRange and positioning', function () {
    expect(VisualMap::piecewise()->outOfRange(['color' => '#ccc'])->left(20)->bottom('10%')->toArray())
        ->toEqual(['type' => 'piecewise', 'outOfRange' => ['color' => '#ccc'], 'left' => 20, 'bottom' => '10%']);
});

it('sets categories for categorical piecewise mapping', function () {
    expect(VisualMap::piecewise()->categories(['A', 'B', 'C'])->toArray())
        ->toEqual(['type' => 'piecewise', 'categories' => ['A', 'B', 'C']]);
});

it('accepts a string or int dimension', function () {
    expect(VisualMap::continuous()->dimension(2)->toArray())
        ->toEqual(['type' => 'continuous', 'dimension' => 2]);

    expect(VisualMap::continuous()->dimension('value')->toArray())
        ->toEqual(['type' => 'continuous', 'dimension' => 'value']);
});

it('sets seriesIndex as a single index or a list', function () {
    expect(VisualMap::continuous()->seriesIndex(1)->toArray())
        ->toEqual(['type' => 'continuous', 'seriesIndex' => 1]);

    expect(VisualMap::continuous()->seriesIndex([0, 1])->toArray())
        ->toEqual(['type' => 'continuous', 'seriesIndex' => [0, 1]]);
});

it('sets precision, text, and hoverLink', function () {
    expect(
        VisualMap::continuous()->min(0)->max(100)->precision(1)->text(['High', 'Low'])->hoverLink(false)->toArray()
    )->toEqual([
        'type' => 'continuous',
        'min' => 0,
        'max' => 100,
        'precision' => 1,
        'text' => ['High', 'Low'],
        'hoverLink' => false,
    ]);
});

it('accepts textStyle as a Label builder or a plain array', function () {
    expect(VisualMap::continuous()->textStyle(Label::make()->color('#333'))->toArray())
        ->toEqual(['type' => 'continuous', 'textStyle' => ['color' => '#333']]);

    expect(VisualMap::continuous()->textStyle(['color' => '#333'])->toArray())
        ->toEqual(['type' => 'continuous', 'textStyle' => ['color' => '#333']]);
});

it('builds itemWidth, itemHeight, range, and splitNumber', function () {
    expect(
        VisualMap::continuous()->itemWidth(20)->itemHeight(140)->range([10, 90])->splitNumber(5)->toArray()
    )->toEqual([
        'type' => 'continuous',
        'itemWidth' => 20,
        'itemHeight' => 140,
        'range' => [10, 90],
        'splitNumber' => 5,
    ]);
});

it('accepts a literal template or a RawJs formatter', function () {
    expect(VisualMap::continuous()->formatter('{value}')->toArray())
        ->toEqual(['type' => 'continuous', 'formatter' => '{value}']);

    expect(VisualMap::continuous()->formatter(RawJs::make('(value) => value.toFixed(1)'))->toArray())
        ->toEqual(['type' => 'continuous', 'formatter' => ['__js__' => '(value) => value.toFixed(1)']]);
});

it('sets realtime, controller, and a fractional precision', function () {
    expect(
        VisualMap::continuous()->realtime(false)->controller(['inRange' => ['color' => ['#eee']]])->precision(0.1)->toArray()
    )->toEqual([
        'type' => 'continuous',
        'realtime' => false,
        'controller' => ['inRange' => ['color' => ['#eee']]],
        'precision' => 0.1,
    ]);
});

it('sets textGap, background/border color and border width', function () {
    expect(
        VisualMap::continuous()
            ->textGap(12)
            ->borderColor('#eee')
            ->backgroundColor('#fff')
            ->borderWidth(1)
            ->toArray()
    )->toEqual([
        'type' => 'continuous',
        'textGap' => 12,
        'borderColor' => '#eee',
        'backgroundColor' => '#fff',
        'borderWidth' => 1,
    ]);
});
