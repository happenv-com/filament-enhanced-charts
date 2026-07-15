<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Enums\Orient;
use Happenv\FilamentEnhancedCharts\Option\Component\Calendar;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;

covers(Calendar::class);

it('builds a calendar with a single-year string range', function () {
    expect(Calendar::make()->range('2017')->toArray())->toEqual([
        'range' => '2017',
    ]);
});

it('builds a calendar with a start/end date range', function () {
    expect(Calendar::make()->range(['2017-01-01', '2017-12-31'])->toArray())->toEqual([
        'range' => ['2017-01-01', '2017-12-31'],
    ]);
});

it('accepts cellSize as a single value or an auto pair', function () {
    expect(Calendar::make()->cellSize(20)->toArray())->toEqual([
        'cellSize' => 20,
    ]);

    expect(Calendar::make()->cellSize(['auto', 20])->toArray())->toEqual([
        'cellSize' => ['auto', 20],
    ]);
});

it('accepts orient as an enum or a string', function () {
    expect(Calendar::make()->orient(Orient::Vertical)->toArray())->toEqual([
        'orient' => 'vertical',
    ]);

    expect(Calendar::make()->orient('horizontal')->toArray())->toEqual([
        'orient' => 'horizontal',
    ]);
});

it('builds dayLabel, monthLabel and yearLabel configs', function () {
    expect(
        Calendar::make()
            ->dayLabel(['firstDay' => 1, 'margin' => 8, 'position' => 'start', 'nameMap' => 'en'])
            ->monthLabel(['margin' => 12, 'position' => 'end', 'formatter' => 'MMM'])
            ->yearLabel(['margin' => 20, 'position' => 'top'])
            ->toArray()
    )->toEqual([
        'dayLabel' => ['firstDay' => 1, 'margin' => 8, 'position' => 'start', 'nameMap' => 'en'],
        'monthLabel' => ['margin' => 12, 'position' => 'end', 'formatter' => 'MMM'],
        'yearLabel' => ['margin' => 20, 'position' => 'top'],
    ]);
});

it('accepts splitLine as a boolean or an array config', function () {
    expect(Calendar::make()->splitLine(true)->toArray())->toEqual([
        'splitLine' => ['show' => true],
    ]);

    expect(Calendar::make()->splitLine(false)->toArray())->toEqual([
        'splitLine' => ['show' => false],
    ]);

    expect(Calendar::make()->splitLine(['show' => true, 'lineStyle' => ['color' => '#000']])->toArray())->toEqual([
        'splitLine' => ['show' => true, 'lineStyle' => ['color' => '#000']],
    ]);
});

it('accepts itemStyle as a builder or an array', function () {
    $viaBuilder = Calendar::make()->itemStyle(ItemStyle::make()->color('#c23531')->borderWidth(1))->toArray();
    $viaArray = Calendar::make()->itemStyle(['color' => '#c23531', 'borderWidth' => 1])->toArray();

    expect($viaBuilder)->toEqual([
        'itemStyle' => ['color' => '#c23531', 'borderWidth' => 1],
    ]);
    expect($viaArray)->toEqual($viaBuilder);
});

it('applies box layout edges via HasLayout', function () {
    expect(Calendar::make()->top(30)->left('5%')->right(20)->bottom('10%')->toArray())->toEqual([
        'left' => '5%',
        'right' => 20,
        'top' => 30,
        'bottom' => '10%',
    ]);
});

it('combines range, cellSize, orient and layout together', function () {
    expect(
        Calendar::make()
            ->range('2017')
            ->cellSize(20)
            ->orient(Orient::Horizontal)
            ->top(30)
            ->toArray()
    )->toEqual([
        'range' => '2017',
        'cellSize' => 20,
        'orient' => 'horizontal',
        'top' => 30,
    ]);
});

it('lets raw() override any typed key on calendar', function () {
    expect(Calendar::make()->range('2017')->cellSize(20)->raw(['cellSize' => 30, 'orient' => 'vertical'])->toArray())
        ->toEqual([
            'range' => '2017',
            'cellSize' => 30,
            'orient' => 'vertical',
        ]);
});
