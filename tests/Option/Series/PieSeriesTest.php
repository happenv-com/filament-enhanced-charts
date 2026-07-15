<?php

declare(strict_types=1);

use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Enums\RoseType;
use Happenv\FilamentEnhancedCharts\Option\Series\PieSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;

covers(PieSeries::class);

it('builds a minimal pie series with data', function () {
    expect(PieSeries::make()->data([['value' => 10, 'name' => 'A']])->toArray())
        ->toEqual(['type' => 'pie', 'data' => [['value' => 10, 'name' => 'A']]]);
});

it('applies startAngle, endAngle, minAngle and clockwise', function () {
    expect(
        PieSeries::make()
            ->startAngle(90)
            ->endAngle(-90)
            ->minAngle(5)
            ->clockwise(false)
            ->data([1])
            ->toArray()
    )->toEqual([
        'type' => 'pie',
        'data' => [1],
        'startAngle' => 90,
        'endAngle' => -90,
        'minAngle' => 5,
        'clockwise' => false,
    ]);
});

it('clockwise defaults to true', function () {
    expect(PieSeries::make()->clockwise()->data([1])->toArray()['clockwise'])->toBeTrue();
});

it('accepts roseType as an enum, a bool, or a string', function () {
    expect(PieSeries::make()->roseType(RoseType::Area)->data([1])->toArray()['roseType'])
        ->toBe('area');

    expect(PieSeries::make()->roseType(true)->data([1])->toArray()['roseType'])
        ->toBe('radius');

    expect(PieSeries::make()->roseType(false)->data([1])->toArray()['roseType'])
        ->toBeFalse();

    expect(PieSeries::make()->roseType('area')->data([1])->toArray()['roseType'])
        ->toBe('area');
});

it('applies avoidLabelOverlap, selectedMode and selectedOffset', function () {
    expect(
        PieSeries::make()
            ->avoidLabelOverlap(false)
            ->selectedMode('multiple')
            ->selectedOffset(15)
            ->data([1])
            ->toArray()
    )->toEqual([
        'type' => 'pie',
        'data' => [1],
        'avoidLabelOverlap' => false,
        'selectedMode' => 'multiple',
        'selectedOffset' => 15,
    ]);
});

it('applies itemStyle from a builder or an array', function () {
    expect(PieSeries::make()->itemStyle(ItemStyle::make()->color('#f00'))->data([1])->toArray()['itemStyle'])
        ->toEqual(['color' => '#f00']);

    expect(PieSeries::make()->itemStyle(['color' => '#0f0'])->data([1])->toArray()['itemStyle'])
        ->toEqual(['color' => '#0f0']);
});

it('accepts labelLine as a boolean or a full config array', function () {
    expect(PieSeries::make()->labelLine(false)->data([1])->toArray()['labelLine'])
        ->toEqual(['show' => false]);

    expect(PieSeries::make()->labelLine(['length' => 20, 'length2' => 10])->data([1])->toArray()['labelLine'])
        ->toEqual(['length' => 20, 'length2' => 10]);
});

it('accepts labelLayout as an array or a RawJs callback', function () {
    expect(PieSeries::make()->labelLayout(['hideOverlap' => true])->data([1])->toArray()['labelLayout'])
        ->toEqual(['hideOverlap' => true]);

    expect(
        PieSeries::make()
            ->labelLayout(RawJs::make('function (params) { return { x: params.rect.x }; }'))
            ->data([1])
            ->toArray()['labelLayout']
    )->toEqual(['__js__' => 'function (params) { return { x: params.rect.x }; }']);
});

it('applies radius and center from HasRadius', function () {
    expect(PieSeries::make()->radius(['40%', '70%'])->center(['50%', '50%'])->data([1])->toArray())
        ->toEqual([
            'type' => 'pie',
            'data' => [1],
            'radius' => ['40%', '70%'],
            'center' => ['50%', '50%'],
        ]);
});

it('lets raw() override a typed pie key', function () {
    expect(PieSeries::make()->clockwise(true)->raw(['clockwise' => false])->toArray()['clockwise'])
        ->toBeFalse();
});

it('applies left/right/top/bottom from HasLayout alongside width and height', function () {
    expect(
        PieSeries::make()
            ->left('5%')
            ->top(10)
            ->width('80%')
            ->height(300)
            ->data([1])
            ->toArray()
    )->toEqual([
        'type' => 'pie',
        'data' => [1],
        'left' => '5%',
        'top' => 10,
        'width' => '80%',
        'height' => 300,
    ]);
});
