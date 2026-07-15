<?php

declare(strict_types=1);

use BcMath\Number;
use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Option\Series\GaugeSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;

covers(GaugeSeries::class);

it('builds a minimal gauge series with data', function () {
    expect(GaugeSeries::make()->data([['value' => 42]])->toArray())
        ->toEqual(['type' => 'gauge', 'data' => [['value' => 42]]]);
});

it('normalizes BcMath min and max to markers', function () {
    expect(GaugeSeries::make()->min(new Number('0'))->max(new Number('100'))->data([['value' => 42]])->toArray())
        ->toEqual([
            'type' => 'gauge',
            'data' => [['value' => 42]],
            'min' => ['__js__' => '0'],
            'max' => ['__js__' => '100'],
        ]);
});

it('applies startAngle, endAngle, clockwise and splitNumber', function () {
    expect(
        GaugeSeries::make()
            ->startAngle(180)
            ->endAngle(0)
            ->clockwise(false)
            ->splitNumber(5)
            ->data([['value' => 10]])
            ->toArray()
    )->toEqual([
        'type' => 'gauge',
        'data' => [['value' => 10]],
        'startAngle' => 180,
        'endAngle' => 0,
        'clockwise' => false,
        'splitNumber' => 5,
    ]);
});

it('clockwise defaults to true', function () {
    expect(GaugeSeries::make()->clockwise()->data([['value' => 1]])->toArray()['clockwise'])->toBeTrue();
});

it('accepts a full axisLine config array with color bands', function () {
    expect(
        GaugeSeries::make()
            ->axisLine([
                'lineStyle' => [
                    'width' => 30,
                    'color' => [[0.3, '#67e0e3'], [0.7, '#37a2da'], [1, '#fd666d']],
                ],
            ])
            ->data([['value' => 10]])
            ->toArray()
    )->toEqual([
        'type' => 'gauge',
        'data' => [['value' => 10]],
        'axisLine' => [
            'lineStyle' => [
                'width' => 30,
                'color' => [[0.3, '#67e0e3'], [0.7, '#37a2da'], [1, '#fd666d']],
            ],
        ],
    ]);
});

it('builds axisLine.lineStyle via axisLineWidth and axisLineColor convenience setters', function () {
    expect(
        GaugeSeries::make()
            ->axisLineWidth(20)
            ->axisLineColor([[0.3, '#67e0e3'], [0.7, '#37a2da'], [1, '#fd666d']])
            ->data([['value' => 10]])
            ->toArray()['axisLine']
    )->toEqual([
        'lineStyle' => [
            'width' => 20,
            'color' => [[0.3, '#67e0e3'], [0.7, '#37a2da'], [1, '#fd666d']],
        ],
    ]);
});

it('normalizes a BcMath stop fraction in an axisLineColor band to a marker', function () {
    expect(
        GaugeSeries::make()
            ->axisLineColor([[new Number('0.5'), '#37a2da']])
            ->data([['value' => 10]])
            ->toArray()['axisLine']['lineStyle']['color']
    )->toEqual([[['__js__' => '0.5'], '#37a2da']]);
});

it('merges axisLineWidth and axisLineColor without clobbering each other', function () {
    $viaWidthFirst = GaugeSeries::make()
        ->axisLineWidth(30)
        ->axisLineColor([[1, '#fd666d']])
        ->data([1])
        ->toArray()['axisLine'];

    $viaColorFirst = GaugeSeries::make()
        ->axisLineColor([[1, '#fd666d']])
        ->axisLineWidth(30)
        ->data([1])
        ->toArray()['axisLine'];

    expect($viaWidthFirst)->toEqual(['lineStyle' => ['width' => 30, 'color' => [[1, '#fd666d']]]]);
    expect($viaColorFirst)->toEqual($viaWidthFirst);
});

it('accepts axisLine as a boolean or a full config array', function () {
    expect(GaugeSeries::make()->axisLine(false)->data([1])->toArray()['axisLine'])
        ->toEqual(['show' => false]);

    expect(GaugeSeries::make()->axisLine(['lineStyle' => ['width' => 10]])->data([1])->toArray()['axisLine'])
        ->toEqual(['lineStyle' => ['width' => 10]]);
});

it('applies itemStyle from a builder or an array', function () {
    expect(GaugeSeries::make()->itemStyle(ItemStyle::make()->color('#f00'))->data([1])->toArray()['itemStyle'])
        ->toEqual(['color' => '#f00']);

    expect(GaugeSeries::make()->itemStyle(['color' => '#0f0'])->data([1])->toArray()['itemStyle'])
        ->toEqual(['color' => '#0f0']);
});

it('accepts axisTick as a boolean or a full config array', function () {
    expect(GaugeSeries::make()->axisTick(false)->data([1])->toArray()['axisTick'])
        ->toEqual(['show' => false]);

    expect(GaugeSeries::make()->axisTick(['splitNumber' => 5, 'length' => 8])->data([1])->toArray()['axisTick'])
        ->toEqual(['splitNumber' => 5, 'length' => 8]);
});

it('accepts splitLine as a boolean or a full config array', function () {
    expect(GaugeSeries::make()->splitLine(false)->data([1])->toArray()['splitLine'])
        ->toEqual(['show' => false]);

    expect(GaugeSeries::make()->splitLine(['length' => 20, 'distance' => 5])->data([1])->toArray()['splitLine'])
        ->toEqual(['length' => 20, 'distance' => 5]);
});

it('accepts axisLabel as a formatter string, a RawJs formatter, or a full config array', function () {
    expect(GaugeSeries::make()->axisLabel('{value}')->data([1])->toArray()['axisLabel'])
        ->toEqual(['formatter' => '{value}']);

    expect(GaugeSeries::make()->axisLabel(RawJs::make('function (v) { return v + "%"; }'))->data([1])->toArray()['axisLabel'])
        ->toEqual(['formatter' => ['__js__' => 'function (v) { return v + "%"; }']]);

    expect(GaugeSeries::make()->axisLabel(['color' => '#333', 'distance' => 10])->data([1])->toArray()['axisLabel'])
        ->toEqual(['color' => '#333', 'distance' => 10]);
});

it('accepts pointer as a boolean or a full config array', function () {
    expect(GaugeSeries::make()->pointer(false)->data([1])->toArray()['pointer'])
        ->toEqual(['show' => false]);

    expect(GaugeSeries::make()->pointer(['icon' => 'circle', 'length' => '60%'])->data([1])->toArray()['pointer'])
        ->toEqual(['icon' => 'circle', 'length' => '60%']);
});

it('accepts anchor as a boolean or a full config array', function () {
    expect(GaugeSeries::make()->anchor(true)->data([1])->toArray()['anchor'])
        ->toEqual(['show' => true]);

    expect(GaugeSeries::make()->anchor(['showAbove' => true, 'size' => 6])->data([1])->toArray()['anchor'])
        ->toEqual(['showAbove' => true, 'size' => 6]);
});

it('applies a title config array', function () {
    expect(
        GaugeSeries::make()->title(['offsetCenter' => [0, '20%'], 'fontSize' => 14])->data([['value' => 1]])->toArray()['title']
    )->toEqual(['offsetCenter' => [0, '20%'], 'fontSize' => 14]);
});

it('applies progress and detail', function () {
    expect(
        GaugeSeries::make()
            ->progress(['show' => true, 'width' => 8])
            ->detail(['valueAnimation' => true, 'formatter' => '{value}'])
            ->data([['value' => 5]])
            ->toArray()
    )->toEqual([
        'type' => 'gauge',
        'data' => [['value' => 5]],
        'progress' => ['show' => true, 'width' => 8],
        'detail' => ['valueAnimation' => true, 'formatter' => '{value}'],
    ]);
});

it('applies radius and center from HasRadius', function () {
    expect(GaugeSeries::make()->radius('80%')->center(['50%', '60%'])->data([1])->toArray())
        ->toEqual([
            'type' => 'gauge',
            'data' => [1],
            'radius' => '80%',
            'center' => ['50%', '60%'],
        ]);
});

it('builds a full rich gauge without needing raw()', function () {
    expect(
        GaugeSeries::make()
            ->startAngle(200)
            ->endAngle(-20)
            ->min(0)
            ->max(100)
            ->splitNumber(10)
            ->axisLineWidth(30)
            ->axisLineColor([[0.3, '#67e0e3'], [0.7, '#37a2da'], [1, '#fd666d']])
            ->pointer(['length' => '60%'])
            ->anchor(['show' => true, 'size' => 6])
            ->title(['fontSize' => 14])
            ->detail(['formatter' => '{value}%'])
            ->radius('90%')
            ->data([['value' => 70, 'name' => 'Score']])
            ->toArray()
    )->toEqual([
        'type' => 'gauge',
        'data' => [['value' => 70, 'name' => 'Score']],
        'min' => 0,
        'max' => 100,
        'startAngle' => 200,
        'endAngle' => -20,
        'splitNumber' => 10,
        'axisLine' => [
            'lineStyle' => [
                'width' => 30,
                'color' => [[0.3, '#67e0e3'], [0.7, '#37a2da'], [1, '#fd666d']],
            ],
        ],
        'pointer' => ['length' => '60%'],
        'anchor' => ['show' => true, 'size' => 6],
        'title' => ['fontSize' => 14],
        'detail' => ['formatter' => '{value}%'],
        'radius' => '90%',
    ]);
});

it('lets raw() override a typed gauge key', function () {
    expect(GaugeSeries::make()->clockwise(true)->raw(['clockwise' => false])->toArray()['clockwise'])
        ->toBeFalse();
});
