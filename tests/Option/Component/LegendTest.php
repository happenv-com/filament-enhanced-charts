<?php

use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Enums\Orient;
use Happenv\FilamentEnhancedCharts\Option\Component\Legend;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;
use Happenv\FilamentEnhancedCharts\Option\Style\LineStyle;

covers(Legend::class);

it('builds show, type, and orient', function () {
    expect(Legend::make()->show(false)->type('scroll')->orient(Orient::Vertical)->toArray())
        ->toEqual(['show' => false, 'type' => 'scroll', 'orient' => 'vertical']);

    expect(Legend::make()->orient('horizontal')->toArray())
        ->toEqual(['orient' => 'horizontal']);
});

it('defaults show() to true', function () {
    expect(Legend::make()->show()->toArray())->toEqual(['show' => true]);
});

it('builds data, textStyle, selectedMode, and itemGap', function () {
    expect(
        Legend::make()
            ->data(['Sales', 'Costs'])
            ->textStyle(['color' => '#333'])
            ->selectedMode(false)
            ->itemGap(12)
            ->toArray()
    )->toEqual([
        'data' => ['Sales', 'Costs'],
        'textStyle' => ['color' => '#333'],
        'selectedMode' => false,
        'itemGap' => 12,
    ]);
});

it('builds align, padding, itemWidth, and itemHeight', function () {
    expect(
        Legend::make()
            ->align('left')
            ->padding([4, 8])
            ->itemWidth(20)
            ->itemHeight(14)
            ->toArray()
    )->toEqual([
        'align' => 'left',
        'padding' => [4, 8],
        'itemWidth' => 20,
        'itemHeight' => 14,
    ]);
});

it('builds icon', function () {
    expect(Legend::make()->icon('circle')->toArray())->toEqual(['icon' => 'circle']);
});

it('accepts a literal template or a RawJs formatter', function () {
    expect(Legend::make()->formatter('{name}')->toArray())
        ->toEqual(['formatter' => '{name}']);

    expect(Legend::make()->formatter(RawJs::make('(name) => name.toUpperCase()'))->toArray())
        ->toEqual(['formatter' => ['__js__' => '(name) => name.toUpperCase()']]);
});

it('normalizes inactiveColor through the Filament color palette', function () {
    expect(Legend::make()->inactiveColor('#ccc')->toArray())
        ->toEqual(['inactiveColor' => '#ccc']);
});

it('accepts itemStyle and lineStyle as builders or plain arrays', function () {
    expect(Legend::make()->itemStyle(ItemStyle::make()->borderWidth(2))->toArray())
        ->toEqual(['itemStyle' => ['borderWidth' => 2]]);

    expect(Legend::make()->itemStyle(['borderWidth' => 2])->toArray())
        ->toEqual(['itemStyle' => ['borderWidth' => 2]]);

    expect(Legend::make()->lineStyle(LineStyle::make()->width(3))->toArray())
        ->toEqual(['lineStyle' => ['width' => 3]]);
});

it('builds width and height', function () {
    expect(Legend::make()->width(200)->height('50%')->toArray())
        ->toEqual(['width' => 200, 'height' => '50%']);
});

it('combines the shared layout edges from HasLayout', function () {
    expect(Legend::make()->left('center')->top(10)->toArray())
        ->toEqual(['left' => 'center', 'top' => 10]);
});

it('sets the initial selected visibility per legend item', function () {
    expect(Legend::make()->selected(['Sales' => false, 'Costs' => true])->toArray())
        ->toEqual(['selected' => ['Sales' => false, 'Costs' => true]]);
});

it('normalizes borderColor and backgroundColor through the Filament color palette', function () {
    expect(Legend::make()->borderColor('#eee')->backgroundColor('#fff')->toArray())
        ->toEqual(['borderColor' => '#eee', 'backgroundColor' => '#fff']);
});

it('builds borderWidth and borderRadius', function () {
    expect(Legend::make()->borderWidth(1.5)->borderRadius([4, 4, 0, 0])->toArray())
        ->toEqual(['borderWidth' => 1.5, 'borderRadius' => [4, 4, 0, 0]]);
});
