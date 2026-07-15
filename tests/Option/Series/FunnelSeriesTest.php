<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Enums\Sort;
use Happenv\FilamentEnhancedCharts\Option\Series\FunnelSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;

covers(FunnelSeries::class);

it('builds a minimal funnel series with data', function () {
    expect(FunnelSeries::make()->data([['value' => 60, 'name' => 'Visit']])->toArray())
        ->toEqual(['type' => 'funnel', 'data' => [['value' => 60, 'name' => 'Visit']]]);
});

it('applies sort from an enum or a bare string', function () {
    expect(FunnelSeries::make()->sort(Sort::Ascending)->data([1])->toArray()['sort'])->toBe('ascending');
    expect(FunnelSeries::make()->sort('none')->data([1])->toArray()['sort'])->toBe('none');
});

it('applies gap, width, height and funnelAlign', function () {
    expect(
        FunnelSeries::make()
            ->gap(4)
            ->width('80%')
            ->height(300)
            ->funnelAlign('left')
            ->data([1])
            ->toArray()
    )->toEqual([
        'type' => 'funnel',
        'data' => [1],
        'gap' => 4,
        'width' => '80%',
        'height' => 300,
        'funnelAlign' => 'left',
    ]);
});

it('applies min, max, minSize and maxSize', function () {
    expect(
        FunnelSeries::make()
            ->min(0)
            ->max(100)
            ->minSize('0%')
            ->maxSize('100%')
            ->data([1])
            ->toArray()
    )->toEqual([
        'type' => 'funnel',
        'data' => [1],
        'min' => 0,
        'max' => 100,
        'minSize' => '0%',
        'maxSize' => '100%',
    ]);
});

it('accepts numeric minSize/maxSize as pixel numbers', function () {
    expect(FunnelSeries::make()->minSize(10)->maxSize(200)->data([1])->toArray())
        ->toEqual(['type' => 'funnel', 'data' => [1], 'minSize' => 10, 'maxSize' => 200]);
});

it('applies itemStyle from a builder or an array', function () {
    expect(FunnelSeries::make()->itemStyle(ItemStyle::make()->color('#f00'))->data([1])->toArray()['itemStyle'])
        ->toEqual(['color' => '#f00']);

    expect(FunnelSeries::make()->itemStyle(['color' => '#0f0'])->data([1])->toArray()['itemStyle'])
        ->toEqual(['color' => '#0f0']);
});

it('accepts labelLine as a boolean or a full config array', function () {
    expect(FunnelSeries::make()->labelLine(false)->data([1])->toArray()['labelLine'])
        ->toEqual(['show' => false]);

    expect(FunnelSeries::make()->labelLine(['length' => 20, 'length2' => 10])->data([1])->toArray()['labelLine'])
        ->toEqual(['length' => 20, 'length2' => 10]);
});

it('applies left/right/top/bottom from HasLayout', function () {
    expect(FunnelSeries::make()->left('5%')->top(20)->data([1])->toArray())
        ->toEqual(['type' => 'funnel', 'data' => [1], 'left' => '5%', 'top' => 20]);
});

it('lets raw() override a typed funnel key', function () {
    expect(FunnelSeries::make()->min(0)->raw(['min' => 10])->toArray()['min'])->toBe(10);
});
