<?php

declare(strict_types=1);

use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Enums\Symbol;
use Happenv\FilamentEnhancedCharts\Option\DataPoint;
use Happenv\FilamentEnhancedCharts\Option\Style\Emphasis;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;

covers(DataPoint::class);

it('builds a minimal data point', function () {
    expect(DataPoint::make(42)->toArray())->toEqual(['value' => 42]);
});

it('applies selected and itemStyle', function () {
    expect(
        DataPoint::make(10)
            ->selected()
            ->itemStyle(ItemStyle::make()->color('#f00'))
            ->toArray()
    )->toEqual([
        'value' => 10,
        'selected' => true,
        'itemStyle' => ['color' => '#f00'],
    ]);
});

it('selected defaults to true and accepts an explicit false', function () {
    expect(DataPoint::make(1)->selected(false)->toArray()['selected'])->toBeFalse();
});

it('applies symbol from an enum or a raw string', function () {
    expect(DataPoint::make(1)->symbol(Symbol::Diamond)->toArray()['symbol'])->toBe('diamond');
    expect(DataPoint::make(1)->symbol('image://foo.png')->toArray()['symbol'])->toBe('image://foo.png');
});

it('applies symbolSize as a fixed size, a pair, or a RawJs callback', function () {
    expect(DataPoint::make(1)->symbolSize(20)->toArray()['symbolSize'])->toBe(20);
    expect(DataPoint::make(1)->symbolSize([10, 20])->toArray()['symbolSize'])->toEqual([10, 20]);
    expect(
        DataPoint::make(1)->symbolSize(RawJs::make('function (v) { return v; }'))->toArray()['symbolSize']
    )->toEqual(['__js__' => 'function (v) { return v; }']);
});

it('applies emphasis from a builder, an array, or a bool', function () {
    expect(DataPoint::make(1)->emphasis(Emphasis::make()->focus('series'))->toArray()['emphasis'])
        ->toEqual(['focus' => 'series']);

    expect(DataPoint::make(1)->emphasis(['scale' => false])->toArray()['emphasis'])
        ->toEqual(['scale' => false]);

    expect(DataPoint::make(1)->emphasis(false)->toArray()['emphasis'])
        ->toEqual(['disabled' => true]);
});

it('applies tooltip, groupId, title and detail', function () {
    expect(
        DataPoint::make(1)
            ->tooltip(['formatter' => '{b}: {c}'])
            ->groupId('group-a')
            ->title(['offsetCenter' => [0, '20%']])
            ->detail(['formatter' => '{value}%'])
            ->toArray()
    )->toEqual([
        'value' => 1,
        'tooltip' => ['formatter' => '{b}: {c}'],
        'groupId' => 'group-a',
        'title' => ['offsetCenter' => [0, '20%']],
        'detail' => ['formatter' => '{value}%'],
    ]);
});

it('accepts labelLine as a boolean or a full config array', function () {
    expect(DataPoint::make(1)->labelLine(false)->toArray()['labelLine'])
        ->toEqual(['show' => false]);

    expect(DataPoint::make(1)->labelLine(['length' => 20])->toArray()['labelLine'])
        ->toEqual(['length' => 20]);
});

it('lets raw() override a typed data point key', function () {
    expect(DataPoint::make(1)->selected(true)->raw(['selected' => false])->toArray()['selected'])
        ->toBeFalse();
});
