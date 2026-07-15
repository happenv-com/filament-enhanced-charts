<?php

use Happenv\FilamentEnhancedCharts\Enums\Symbol;
use Happenv\FilamentEnhancedCharts\Option\Series\PictorialBarSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;

covers(PictorialBarSeries::class);

it('builds a minimal pictorial bar series with data', function () {
    expect(PictorialBarSeries::make()->data([1, 2, 3])->toArray())
        ->toBe(['type' => 'pictorialBar', 'data' => [1, 2, 3]]);
});

it('applies a Symbol enum', function () {
    expect(PictorialBarSeries::make()->symbol(Symbol::Rect)->data([1])->toArray())
        ->toBe(['type' => 'pictorialBar', 'data' => [1], 'symbol' => 'rect']);
});

it('accepts a custom image:// symbol string', function () {
    expect(PictorialBarSeries::make()->symbol('image://https://example.com/car.png')->data([1])->toArray())
        ->toBe(['type' => 'pictorialBar', 'data' => [1], 'symbol' => 'image://https://example.com/car.png']);
});

it('accepts an array symbolSize relative to symbolBoundingData', function () {
    expect(PictorialBarSeries::make()->symbolSize(['100%', '50%'])->data([1])->toArray())
        ->toBe(['type' => 'pictorialBar', 'data' => [1], 'symbolSize' => ['100%', '50%']]);
});

it('applies symbolRepeat, symbolClip and symbolBoundingData', function () {
    expect(
        PictorialBarSeries::make()
            ->symbolRepeat()
            ->symbolClip()
            ->symbolBoundingData(100)
            ->data([1])
            ->toArray()
    )->toBe([
        'type' => 'pictorialBar',
        'data' => [1],
        'symbolRepeat' => true,
        'symbolClip' => true,
        'symbolBoundingData' => 100,
    ]);
});

it('accepts a fixed repeat count and the fixed keyword', function () {
    expect(PictorialBarSeries::make()->symbolRepeat(3)->data([1])->toArray()['symbolRepeat'])
        ->toBe(3);

    expect(PictorialBarSeries::make()->symbolRepeat('fixed')->data([1])->toArray()['symbolRepeat'])
        ->toBe('fixed');
});

it('applies symbolPosition, symbolOffset and symbolMargin', function () {
    expect(
        PictorialBarSeries::make()
            ->symbolPosition('center')
            ->symbolOffset([0, '-10%'])
            ->symbolMargin('10%')
            ->data([1])
            ->toArray()
    )->toBe([
        'type' => 'pictorialBar',
        'data' => [1],
        'symbolPosition' => 'center',
        'symbolOffset' => [0, '-10%'],
        'symbolMargin' => '10%',
    ]);
});

it('applies barCategoryGap and barGap', function () {
    expect(PictorialBarSeries::make()->barCategoryGap('20%')->barGap('10%')->data([1])->toArray())
        ->toBe(['type' => 'pictorialBar', 'data' => [1], 'barCategoryGap' => '20%', 'barGap' => '10%']);
});

it('applies a Label builder', function () {
    expect(PictorialBarSeries::make()->label(['show' => true, 'position' => 'top'])->data([1])->toArray()['label'])
        ->toBe(['show' => true, 'position' => 'top']);
});

it('applies an ItemStyle builder to itemStyle', function () {
    expect(PictorialBarSeries::make()->itemStyle(ItemStyle::make()->color('#f00'))->data([1])->toArray()['itemStyle'])
        ->toBe(['color' => '#f00']);
});

it('lets raw() override a typed key', function () {
    expect(PictorialBarSeries::make()->symbolClip(true)->raw(['symbolClip' => false])->toArray()['symbolClip'])
        ->toBeFalse();
});
