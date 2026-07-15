<?php

use Happenv\FilamentEnhancedCharts\Option\Component\Radar;

covers(Radar::class);

it('builds an indicator list', function () {
    expect(Radar::make()->indicator([['name' => 'A', 'max' => 10]])->toArray())
        ->toEqual(['indicator' => [['name' => 'A', 'max' => 10]]]);
});

it('builds radius and center via HasRadius', function () {
    expect(Radar::make()->radius('60%')->center(['50%', '50%'])->toArray())
        ->toEqual([
            'indicator' => [],
            'radius' => '60%',
            'center' => ['50%', '50%'],
        ]);
});

it('builds shape and splitNumber', function () {
    expect(Radar::make()->shape('circle')->splitNumber(4)->toArray())
        ->toEqual(['indicator' => [], 'shape' => 'circle', 'splitNumber' => 4]);
});

it('builds axisName as a full config array', function () {
    expect(Radar::make()->axisName(['show' => false])->toArray())
        ->toEqual(['indicator' => [], 'axisName' => ['show' => false]]);
});

it('applies axisLine as a show flag or a config array', function () {
    expect(Radar::make()->axisLine(false)->toArray())
        ->toEqual(['indicator' => [], 'axisLine' => ['show' => false]]);

    expect(Radar::make()->axisLine(['lineStyle' => ['color' => '#ccc']])->toArray())
        ->toEqual(['indicator' => [], 'axisLine' => ['lineStyle' => ['color' => '#ccc']]]);
});

it('applies splitLine as a show flag or a config array', function () {
    expect(Radar::make()->splitLine()->toArray())
        ->toEqual(['indicator' => [], 'splitLine' => ['show' => true]]);

    expect(Radar::make()->splitLine(['lineStyle' => ['type' => 'dashed']])->toArray())
        ->toEqual(['indicator' => [], 'splitLine' => ['lineStyle' => ['type' => 'dashed']]]);
});

it('applies splitArea as a show flag or a config array', function () {
    expect(Radar::make()->splitArea(false)->toArray())
        ->toEqual(['indicator' => [], 'splitArea' => ['show' => false]]);

    expect(Radar::make()->splitArea(['areaStyle' => ['color' => ['#f00']]])->toArray())
        ->toEqual(['indicator' => [], 'splitArea' => ['areaStyle' => ['color' => ['#f00']]]]);
});

it('builds scale and axisLabel', function () {
    expect(Radar::make()->scale()->axisLabel(['show' => true, 'color' => '#999'])->toArray())
        ->toEqual([
            'indicator' => [],
            'scale' => true,
            'axisLabel' => ['show' => true, 'color' => '#999'],
        ]);
});

it('builds nameGap as the ECharts axisNameGap key', function () {
    expect(Radar::make()->nameGap(20)->toArray())
        ->toEqual(['indicator' => [], 'axisNameGap' => 20]);
});

it('builds startAngle', function () {
    expect(Radar::make()->startAngle(90)->toArray())
        ->toEqual(['indicator' => [], 'startAngle' => 90]);
});

it('lets raw() override a typed key', function () {
    expect(Radar::make()->shape('polygon')->raw(['shape' => 'circle'])->toArray())
        ->toEqual(['indicator' => [], 'shape' => 'circle']);
});
