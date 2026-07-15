<?php

declare(strict_types=1);

use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Option\Series\BarSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\LineSeries;

it('applies z and zlevel on a series', function () {
    expect(BarSeries::make()->z(2)->zlevel(1)->data([1])->toArray())
        ->toEqual(['type' => 'bar', 'data' => [1], 'z' => 2, 'zlevel' => 1]);
});

it('applies silent on a series', function () {
    expect(BarSeries::make()->silent()->data([1])->toArray())
        ->toEqual(['type' => 'bar', 'data' => [1], 'silent' => true]);
});

it('applies large and largeThreshold on a series', function () {
    expect(BarSeries::make()->large()->largeThreshold(500)->data([1])->toArray())
        ->toEqual(['type' => 'bar', 'data' => [1], 'large' => true, 'largeThreshold' => 500]);
});

it('applies clip, colorBy, cursor and legendHoverLink on a series', function () {
    expect(
        BarSeries::make()->clip(false)->colorBy('data')->cursor('pointer')->legendHoverLink(false)->data([1])->toArray()
    )->toEqual([
        'type' => 'bar',
        'data' => [1],
        'clip' => false,
        'colorBy' => 'data',
        'cursor' => 'pointer',
        'legendHoverLink' => false,
    ]);
});

it('applies sampling, progressive and progressiveThreshold on a series', function () {
    expect(
        LineSeries::make()->sampling('lttb')->progressive(400)->progressiveThreshold(3000)->data([1])->toArray()
    )->toEqual([
        'type' => 'line',
        'data' => [1],
        'sampling' => 'lttb',
        'progressive' => 400,
        'progressiveThreshold' => 3000,
    ]);
});

it('accepts selectedMode as a boolean or a string on a base series', function () {
    expect(BarSeries::make()->selectedMode(true)->data([1])->toArray()['selectedMode'])->toBeTrue();
    expect(BarSeries::make()->selectedMode('multiple')->data([1])->toArray()['selectedMode'])->toBe('multiple');
});

it('applies a per-series tooltip override on a series', function () {
    expect(BarSeries::make()->tooltip(['formatter' => '{b}: {c}'])->data([1])->toArray())
        ->toEqual(['type' => 'bar', 'data' => [1], 'tooltip' => ['formatter' => '{b}: {c}']]);
});

it('applies labelLayout as an array or a RawJs callback on a series', function () {
    expect(BarSeries::make()->labelLayout(['hideOverlap' => true])->data([1])->toArray())
        ->toEqual(['type' => 'bar', 'data' => [1], 'labelLayout' => ['hideOverlap' => true]]);

    expect(BarSeries::make()->labelLayout(RawJs::make('params => ({dx: 10})'))->data([1])->toArray())
        ->toEqual(['type' => 'bar', 'data' => [1], 'labelLayout' => ['__js__' => 'params => ({dx: 10})']]);
});

it('applies labelLine on a series that does not override it', function () {
    expect(LineSeries::make()->labelLine(['show' => false])->data([1])->toArray())
        ->toEqual(['type' => 'line', 'data' => [1], 'labelLine' => ['show' => false]]);
});

it('converts a bool labelLine into a show toggle on the base series', function () {
    expect(LineSeries::make()->labelLine(false)->data([1])->toArray())
        ->toEqual(['type' => 'line', 'data' => [1], 'labelLine' => ['show' => false]]);
});

it('applies animation controls on a series', function () {
    expect(
        BarSeries::make()
            ->animation(false)
            ->animationThreshold(2000)
            ->animationDuration(1000)
            ->animationEasing('cubicOut')
            ->animationDelay(100)
            ->animationDurationUpdate(300)
            ->animationEasingUpdate('linear')
            ->animationDelayUpdate(0)
            ->data([1])
            ->toArray()
    )->toEqual([
        'type' => 'bar',
        'data' => [1],
        'animation' => false,
        'animationThreshold' => 2000,
        'animationDuration' => 1000,
        'animationEasing' => 'cubicOut',
        'animationDelay' => 100,
        'animationDurationUpdate' => 300,
        'animationEasingUpdate' => 'linear',
        'animationDelayUpdate' => 0,
    ]);
});

it('accepts a RawJs callback for animationDuration', function () {
    expect(BarSeries::make()->animationDuration(RawJs::make('idx => idx * 100'))->data([1])->toArray())
        ->toEqual(['type' => 'bar', 'data' => [1], 'animationDuration' => ['__js__' => 'idx => idx * 100']]);
});

it('does not emit unset series-level keys', function () {
    expect(BarSeries::make()->data([1])->toArray())
        ->toEqual(['type' => 'bar', 'data' => [1]]);
});

it('applies id on a series', function () {
    expect(BarSeries::make()->id('sales-2026')->data([1])->toArray())
        ->toEqual(['type' => 'bar', 'data' => [1], 'id' => 'sales-2026']);
});

it('applies blendMode on a series', function () {
    expect(BarSeries::make()->blendMode('lighter')->data([1])->toArray())
        ->toEqual(['type' => 'bar', 'data' => [1], 'blendMode' => 'lighter']);
});

it('applies dimensions and seriesLayoutBy on a series', function () {
    expect(
        LineSeries::make()->dimensions(['date', 'sales'])->seriesLayoutBy('row')->data([1])->toArray()
    )->toEqual([
        'type' => 'line',
        'data' => [1],
        'dimensions' => ['date', 'sales'],
        'seriesLayoutBy' => 'row',
    ]);
});
