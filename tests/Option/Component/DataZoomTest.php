<?php

use Happenv\FilamentEnhancedCharts\Enums\DataZoomFilterMode;
use Happenv\FilamentEnhancedCharts\Option\Component\DataZoom;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;

it('builds a slider data zoom with start, end and a single x-axis index', function () {
    expect(DataZoom::slider()->start(0)->end(50)->xAxisIndex(0)->toArray())
        ->toBe(['type' => 'slider', 'start' => 0, 'end' => 50, 'xAxisIndex' => 0]);
});

it('sets the filter mode from the enum', function () {
    expect(DataZoom::slider()->filterMode(DataZoomFilterMode::WeakFilter)->toArray())
        ->toEqual(['type' => 'slider', 'filterMode' => 'weakFilter']);
});

it('sets the filter mode from a bare string', function () {
    expect(DataZoom::slider()->filterMode('none')->toArray())
        ->toEqual(['type' => 'slider', 'filterMode' => 'none']);
});

it('sets realtime', function () {
    expect(DataZoom::inside()->realtime(false)->toArray())
        ->toEqual(['type' => 'inside', 'realtime' => false]);
});

it('sets start/end value', function () {
    expect(DataZoom::slider()->startValue(10)->endValue('2026-07-01')->toArray())
        ->toEqual(['type' => 'slider', 'startValue' => 10, 'endValue' => '2026-07-01']);
});

it('sets show, min/max span, zoom lock, throttle and handle styling', function () {
    expect(
        DataZoom::slider()
            ->show(false)
            ->minSpan(5)
            ->maxSpan(50)
            ->zoomLock(true)
            ->throttle(100)
            ->handleIcon('path://M0,0')
            ->handleSize('80%')
            ->toArray()
    )->toEqual([
        'type' => 'slider',
        'show' => false,
        'minSpan' => 5,
        'maxSpan' => 50,
        'zoomLock' => true,
        'throttle' => 100,
        'handleIcon' => 'path://M0,0',
        'handleSize' => '80%',
    ]);
});

it('sets min/max value span, data background, brush select and mouse interactions', function () {
    expect(
        DataZoom::inside()
            ->minValueSpan(1)
            ->maxValueSpan(100)
            ->dataBackground(['lineStyle' => ['color' => '#ccc']])
            ->brushSelect(false)
            ->zoomOnMouseWheel('shift')
            ->moveOnMouseMove(true)
            ->moveOnMouseWheel(false)
            ->toArray()
    )->toEqual([
        'type' => 'inside',
        'minValueSpan' => 1,
        'maxValueSpan' => 100,
        'dataBackground' => ['lineStyle' => ['color' => '#ccc']],
        'brushSelect' => false,
        'zoomOnMouseWheel' => 'shift',
        'moveOnMouseMove' => true,
        'moveOnMouseWheel' => false,
    ]);
});

it('sets the label formatter, zlevel and z', function () {
    expect(DataZoom::slider()->labelFormatter('{value}')->zlevel(1)->z(2)->toArray())
        ->toEqual(['type' => 'slider', 'labelFormatter' => '{value}', 'zlevel' => 1, 'z' => 2]);
});

it('emits multiple data zoom entries as a list', function () {
    expect(Option::make()->dataZoom(DataZoom::slider(), DataZoom::inside())->toArray()['dataZoom'])
        ->toBe([
            ['type' => 'slider'],
            ['type' => 'inside'],
        ]);
});

it('emits a single data zoom entry as an object', function () {
    expect(Option::make()->dataZoom(DataZoom::inside())->toArray()['dataZoom'])
        ->toBe(['type' => 'inside']);
});

it('accepts textStyle as a Label builder or a plain array', function () {
    expect(DataZoom::slider()->textStyle(Label::make()->color('#333'))->toArray())
        ->toEqual(['type' => 'slider', 'textStyle' => ['color' => '#333']]);

    expect(DataZoom::slider()->textStyle(['fontSize' => 12])->toArray())
        ->toEqual(['type' => 'slider', 'textStyle' => ['fontSize' => 12]]);
});

it('sets showDataShadow, showDetail and id', function () {
    expect(
        DataZoom::slider()->showDataShadow('auto')->showDetail(false)->id('myZoom')->toArray()
    )->toEqual([
        'type' => 'slider',
        'showDataShadow' => 'auto',
        'showDetail' => false,
        'id' => 'myZoom',
    ]);
});
