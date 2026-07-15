<?php

declare(strict_types=1);

use BcMath\Number;
use Happenv\FilamentEnhancedCharts\Enums\Symbol;
use Happenv\FilamentEnhancedCharts\Option\Series\EffectScatterSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;

covers(EffectScatterSeries::class);

it('builds an effectScatter series with symbol size and data', function () {
    expect(EffectScatterSeries::make()->symbolSize(10)->data([[10, 20], [15, 30]])->toArray())
        ->toEqual([
            'type' => 'effectScatter',
            'data' => [[10, 20], [15, 30]],
            'symbolSize' => 10,
        ]);
});

it('applies a symbol on an effectScatter series', function () {
    expect(EffectScatterSeries::make()->symbol(Symbol::Rect)->data([[1, 2]])->toArray())
        ->toEqual(['type' => 'effectScatter', 'data' => [[1, 2]], 'symbol' => 'rect']);
});

it('applies effectType and showEffectOn', function () {
    expect(EffectScatterSeries::make()->effectType()->showEffectOn('emphasis')->data([[1, 2]])->toArray())
        ->toEqual([
            'type' => 'effectScatter',
            'data' => [[1, 2]],
            'effectType' => 'ripple',
            'showEffectOn' => 'emphasis',
        ]);
});

it('defaults effectType to ripple when called without arguments', function () {
    expect(EffectScatterSeries::make()->effectType()->toArray()['effectType'])->toBe('ripple');
});

it('applies a rippleEffect config', function () {
    expect(
        EffectScatterSeries::make()
            ->rippleEffect(['period' => 4, 'scale' => 2.5, 'brushType' => 'stroke', 'color' => '#f00', 'number' => 3])
            ->data([[1, 2]])
            ->toArray()
    )->toEqual([
        'type' => 'effectScatter',
        'data' => [[1, 2]],
        'rippleEffect' => ['period' => 4, 'scale' => 2.5, 'brushType' => 'stroke', 'color' => '#f00', 'number' => 3],
    ]);
});

it('normalizes a BcMath value inside rippleEffect to a marker', function () {
    expect(
        EffectScatterSeries::make()->rippleEffect(['scale' => new Number('2.50')])->toArray()['rippleEffect']
    )->toEqual(['scale' => ['__js__' => '2.5']]);
});

it('normalizes a BcMath value in effectScatter data to a marker', function () {
    expect(EffectScatterSeries::make()->data([[0, 0, new Number('3.00')]])->toArray()['data'])
        ->toEqual([[0, 0, ['__js__' => '3']]]);
});

it('applies itemStyle as a builder or an array', function () {
    $viaBuilder = EffectScatterSeries::make()->itemStyle(ItemStyle::make()->color('#3b82f6'))->data([[1, 2]])->toArray();
    $viaArray = EffectScatterSeries::make()->itemStyle(['color' => '#3b82f6'])->data([[1, 2]])->toArray();

    expect($viaBuilder)->toEqual([
        'type' => 'effectScatter',
        'data' => [[1, 2]],
        'itemStyle' => ['color' => '#3b82f6'],
    ]);
    expect($viaArray)->toBe($viaBuilder);
});

it('lets raw() override an effectScatter series effectType', function () {
    expect(EffectScatterSeries::make()->effectType()->raw(['effectType' => 'other'])->toArray()['effectType'])
        ->toBe('other');
});
