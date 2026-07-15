<?php

use Happenv\FilamentEnhancedCharts\Enums\Symbol;
use Happenv\FilamentEnhancedCharts\Option\Series\BarSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\PieSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\ScatterSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;

it('builds a bar series with stack and itemStyle', function () {
    expect(BarSeries::make()->stack('x')->itemStyle(['color' => '#22c55e'])->data([1, 2])->toArray())
        ->toBe(['type' => 'bar', 'itemStyle' => ['color' => '#22c55e'], 'data' => [1, 2], 'stack' => 'x']);
});

it('applies barWidth on a bar series', function () {
    expect(BarSeries::make()->barWidth(20)->data([1])->toArray())
        ->toBe(['type' => 'bar', 'data' => [1], 'barWidth' => 20]);
});

it('lets raw() override a bar series stack', function () {
    expect(BarSeries::make()->stack('x')->raw(['stack' => 'y'])->toArray()['stack'])
        ->toBe('y');
});

it('applies barGap and barCategoryGap on a bar series', function () {
    expect(BarSeries::make()->barGap('30%')->barCategoryGap(20)->data([1])->toArray())
        ->toEqual(['type' => 'bar', 'data' => [1], 'barGap' => '30%', 'barCategoryGap' => 20]);
});

it('applies showBackground, backgroundStyle and roundCap on a bar series', function () {
    expect(
        BarSeries::make()
            ->showBackground()
            ->backgroundStyle(['color' => 'rgba(180, 180, 180, 0.2)'])
            ->roundCap()
            ->data([1])
            ->toArray()
    )->toEqual([
        'type' => 'bar',
        'data' => [1],
        'showBackground' => true,
        'backgroundStyle' => ['color' => 'rgba(180, 180, 180, 0.2)'],
        'roundCap' => true,
    ]);
});

it('builds a scatter series with symbol size', function () {
    expect(ScatterSeries::make()->symbolSize(6)->data([[1, 2]])->toArray())
        ->toBe(['type' => 'scatter', 'data' => [[1, 2]], 'symbolSize' => 6]);
});

it('applies itemStyle on a scatter series', function () {
    expect(ScatterSeries::make()->itemStyle(['color' => '#3b82f6'])->data([[1, 2]])->toArray())
        ->toBe(['type' => 'scatter', 'itemStyle' => ['color' => '#3b82f6'], 'data' => [[1, 2]]]);
});

it('applies a symbol on a scatter series', function () {
    expect(ScatterSeries::make()->symbol(Symbol::Rect)->data([[1, 2]])->toArray())
        ->toBe(['type' => 'scatter', 'data' => [[1, 2]], 'symbol' => 'rect']);
});

it('sets a series-level color from a CSS string or Filament palette', function () {
    expect(BarSeries::make()->color('#f00')->data([1])->toArray())
        ->toBe(['type' => 'bar', 'color' => '#f00', 'data' => [1]]);

    expect(BarSeries::make()->color([500 => 'base'])->data([1])->toArray())
        ->toBe(['type' => 'bar', 'color' => 'base', 'data' => [1]]);
});

it('builds a pie series with radius and rose type', function () {
    expect(PieSeries::make()->radius(['40%', '70%'])->roseType('area')->data([1])->toArray())
        ->toBe(['type' => 'pie', 'data' => [1], 'radius' => ['40%', '70%'], 'roseType' => 'area']);
});

it('applies center and label on a pie series', function () {
    expect(PieSeries::make()->center(['50%', '50%'])->label(['show' => true])->data([1])->toArray())
        ->toEqual(['type' => 'pie', 'data' => [1], 'center' => ['50%', '50%'], 'label' => ['show' => true]]);
});

it('applies itemStyle on a pie series', function () {
    $viaBuilder = PieSeries::make()->itemStyle(ItemStyle::make()->borderColor('#fff')->borderWidth(2))->data([1])->toArray();
    $viaArray = PieSeries::make()->itemStyle(['borderColor' => '#fff', 'borderWidth' => 2])->data([1])->toArray();

    expect($viaBuilder)->toEqual([
        'type' => 'pie',
        'data' => [1],
        'itemStyle' => ['borderColor' => '#fff', 'borderWidth' => 2],
    ]);
    expect($viaArray)->toBe($viaBuilder);
});

it('applies labelLine on a pie series', function () {
    expect(PieSeries::make()->labelLine(['length' => 10, 'length2' => 20])->data([1])->toArray())
        ->toEqual(['type' => 'pie', 'data' => [1], 'labelLine' => ['length' => 10, 'length2' => 20]]);
});

it('accepts selectedMode as a boolean or a string on a pie series', function () {
    expect(PieSeries::make()->selectedMode(true)->data([1])->toArray()['selectedMode'])->toBeTrue();
    expect(PieSeries::make()->selectedMode('multiple')->data([1])->toArray()['selectedMode'])->toBe('multiple');
});

it('applies padAngle and avoidLabelOverlap on a pie series', function () {
    expect(PieSeries::make()->padAngle(2)->avoidLabelOverlap()->data([1])->toArray())
        ->toEqual(['type' => 'pie', 'data' => [1], 'padAngle' => 2, 'avoidLabelOverlap' => true]);

    expect(PieSeries::make()->avoidLabelOverlap(false)->data([1])->toArray()['avoidLabelOverlap'])->toBeFalse();
});

it('lets raw() override a pie series itemStyle', function () {
    expect(
        PieSeries::make()->itemStyle(['borderColor' => '#fff'])->raw(['itemStyle' => ['borderColor' => '#000']])->toArray()['itemStyle']
    )->toBe(['borderColor' => '#000']);
});
