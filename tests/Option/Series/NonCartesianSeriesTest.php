<?php

use BcMath\Number;
use Happenv\FilamentEnhancedCharts\Option\Series\FunnelSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\GaugeSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\SunburstSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;

it('builds a funnel with sort', function () {
    expect(FunnelSeries::make()->sort('descending')->data([['value' => 1, 'name' => 'A']])->toArray())
        ->toBe(['type' => 'funnel', 'data' => [['value' => 1, 'name' => 'A']], 'sort' => 'descending']);
});

it('applies gap and label on a funnel series', function () {
    expect(FunnelSeries::make()->gap(2)->label(['show' => true])->data([['value' => 1]])->toArray())
        ->toBe(['type' => 'funnel', 'label' => ['show' => true], 'data' => [['value' => 1]], 'gap' => 2]);
});

it('lets raw() override a funnel series sort', function () {
    expect(FunnelSeries::make()->sort('descending')->raw(['sort' => 'ascending'])->toArray()['sort'])
        ->toBe('ascending');
});

it('builds a gauge with BcMath max', function () {
    expect(GaugeSeries::make()->max(new Number('100'))->data([['value' => 42]])->toArray())
        ->toBe(['type' => 'gauge', 'data' => [['value' => 42]], 'max' => ['__js__' => '100']]);
});

it('applies min, progress and detail on a gauge series', function () {
    expect(
        GaugeSeries::make()
            ->min(0)
            ->progress(['show' => true])
            ->detail(['formatter' => '{value}'])
            ->data([['value' => 5]])
            ->toArray()
    )->toBe([
        'type' => 'gauge',
        'data' => [['value' => 5]],
        'min' => 0,
        'progress' => ['show' => true],
        'detail' => ['formatter' => '{value}'],
    ]);
});

it('builds a sunburst with nested data', function () {
    expect(SunburstSeries::make()->radius(['0%', '90%'])->data([['name' => 'A']])->toArray())
        ->toBe(['type' => 'sunburst', 'data' => [['name' => 'A']], 'radius' => ['0%', '90%']]);
});

it('applies itemStyle on a sunburst series', function () {
    $viaBuilder = SunburstSeries::make()->itemStyle(ItemStyle::make()->color('#c23531'))->data([['name' => 'A']])->toArray();
    $viaArray = SunburstSeries::make()->itemStyle(['color' => '#c23531'])->data([['name' => 'A']])->toArray();

    expect($viaBuilder)->toEqual([
        'type' => 'sunburst',
        'data' => [['name' => 'A']],
        'itemStyle' => ['color' => '#c23531'],
    ]);
    expect($viaArray)->toBe($viaBuilder);
});

it('applies levels on a sunburst series', function () {
    expect(
        SunburstSeries::make()
            ->levels([['r0' => '15%', 'r' => '35%'], ['r0' => '35%', 'r' => '70%']])
            ->data([['name' => 'A']])
            ->toArray()
    )->toEqual([
        'type' => 'sunburst',
        'data' => [['name' => 'A']],
        'levels' => [['r0' => '15%', 'r' => '35%'], ['r0' => '35%', 'r' => '70%']],
    ]);
});

it('normalizes a BcMath value inside sunburst levels to a marker', function () {
    expect(SunburstSeries::make()->levels([['itemStyle' => ['borderWidth' => new Number('1.50')]]])->toArray()['levels'])
        ->toEqual([['itemStyle' => ['borderWidth' => ['__js__' => '1.5']]]]);
});

it('applies and clears sort on a sunburst series', function () {
    expect(SunburstSeries::make()->sort('asc')->data([['name' => 'A']])->toArray())
        ->toEqual(['type' => 'sunburst', 'data' => [['name' => 'A']], 'sort' => 'asc']);

    expect(SunburstSeries::make()->sort('asc')->sort(null)->data([['name' => 'A']])->toArray())
        ->toEqual(['type' => 'sunburst', 'data' => [['name' => 'A']]]);
});
