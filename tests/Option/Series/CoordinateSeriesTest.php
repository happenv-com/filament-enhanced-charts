<?php

use Happenv\FilamentEnhancedCharts\Enums\Symbol;
use Happenv\FilamentEnhancedCharts\Option\Component\Parallel;
use Happenv\FilamentEnhancedCharts\Option\Component\ParallelAxis;
use Happenv\FilamentEnhancedCharts\Option\Component\Radar;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\ParallelSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\RadarSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\AreaStyle;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;
use Happenv\FilamentEnhancedCharts\Option\Style\LineStyle;

it('composes a radar option', function () {
    $option = Option::make()
        ->radar(Radar::make()->indicator([['name' => 'A', 'max' => 10]]))
        ->series(RadarSeries::make()->data([['value' => [3], 'name' => 'x']]));

    expect($option->toArray())->toBe([
        'radar' => ['indicator' => [['name' => 'A', 'max' => 10]]],
        'series' => [['type' => 'radar', 'data' => [['value' => [3], 'name' => 'x']]]],
    ]);
});

it('composes a parallel option with axes', function () {
    $option = Option::make()
        ->parallelAxis(ParallelAxis::make()->dim(0)->name('A'), ParallelAxis::make()->dim(1)->name('B'))
        ->series(ParallelSeries::make()->data([[1, 2]]));

    expect($option->toArray())->toBe([
        'parallelAxis' => [['dim' => 0, 'name' => 'A'], ['dim' => 1, 'name' => 'B']],
        'series' => [['type' => 'parallel', 'data' => [[1, 2]]]],
    ]);
});

it('emits a parallel key from Option::parallel()', function () {
    expect(Option::make()->parallel(Parallel::make())->toArray())
        ->toBe(['parallel' => []]);
});

it('lets raw() override a value on a new coordinate component', function () {
    expect(
        Radar::make()->indicator([['name' => 'A', 'max' => 5]])->raw(['shape' => 'circle'])->toArray()
    )->toBe([
        'indicator' => [['name' => 'A', 'max' => 5]],
        'shape' => 'circle',
    ]);
});

it('emits parallelAxis as a list even with a single axis', function () {
    expect(Option::make()->parallelAxis(ParallelAxis::make()->dim(0)->name('A'))->toArray())
        ->toBe(['parallelAxis' => [['dim' => 0, 'name' => 'A']]]);
});

it('applies itemStyle, lineStyle and areaStyle builders to a radar series', function () {
    expect(
        RadarSeries::make()
            ->itemStyle(ItemStyle::make()->color('#f00'))
            ->lineStyle(LineStyle::make()->width(2))
            ->areaStyle(AreaStyle::make()->opacity(0.3))
            ->data([['value' => [1, 2, 3]]])
            ->toArray()
    )->toEqual([
        'type' => 'radar',
        'data' => [['value' => [1, 2, 3]]],
        'itemStyle' => ['color' => '#f00'],
        'lineStyle' => ['width' => 2],
        'areaStyle' => ['opacity' => 0.3],
    ]);
});

it('accepts plain arrays for itemStyle, lineStyle and areaStyle on a radar series', function () {
    expect(
        RadarSeries::make()
            ->itemStyle(['color' => '#00f'])
            ->lineStyle(['type' => 'dashed'])
            ->areaStyle(['origin' => 'start'])
            ->toArray()
    )->toEqual([
        'type' => 'radar',
        'itemStyle' => ['color' => '#00f'],
        'lineStyle' => ['type' => 'dashed'],
        'areaStyle' => ['origin' => 'start'],
    ]);
});

it('applies a symbol and symbolSize to a radar series', function () {
    expect(RadarSeries::make()->symbol(Symbol::Diamond)->symbolSize(8)->toArray())
        ->toEqual(['type' => 'radar', 'symbol' => 'diamond', 'symbolSize' => 8]);
});

it('lets raw() override a typed key on a radar series', function () {
    expect(
        RadarSeries::make()->itemStyle(['color' => '#f00'])->raw(['itemStyle' => ['color' => '#0f0']])->toArray()
    )->toEqual(['type' => 'radar', 'itemStyle' => ['color' => '#0f0']]);
});

it('applies lineStyle, opacities and smooth to a parallel series', function () {
    expect(
        ParallelSeries::make()
            ->lineStyle(LineStyle::make()->width(1))
            ->inactiveOpacity(0.2)
            ->activeOpacity(1)
            ->smooth()
            ->data([[1, 2]])
            ->toArray()
    )->toEqual([
        'type' => 'parallel',
        'data' => [[1, 2]],
        'lineStyle' => ['width' => 1],
        'inactiveOpacity' => 0.2,
        'activeOpacity' => 1,
        'smooth' => true,
    ]);
});

it('accepts a plain array for lineStyle on a parallel series', function () {
    expect(ParallelSeries::make()->lineStyle(['color' => '#f00'])->toArray())
        ->toEqual(['type' => 'parallel', 'lineStyle' => ['color' => '#f00']]);
});

it('lets raw() override a typed key on a parallel series', function () {
    expect(ParallelSeries::make()->smooth()->raw(['smooth' => false])->toArray())
        ->toEqual(['type' => 'parallel', 'smooth' => false]);
});
