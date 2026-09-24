<?php

declare(strict_types=1);

use Filament\Support\Colors\Color;
use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Option\Series\BarSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\AreaStyle;
use Happenv\FilamentEnhancedCharts\Option\Style\Emphasis;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;
use Happenv\FilamentEnhancedCharts\Option\Style\LineStyle;

it('builds an item style with shared and specific keys', function (): void {
    expect(
        ItemStyle::make()->color('#22c55e')->opacity(0.8)->shadow(6, '#333')
            ->borderColor('#000')->borderWidth(2)->borderRadius(4)->toArray()
    )->toEqual([
        'color' => '#22c55e',
        'opacity' => 0.8,
        'shadowBlur' => 6,
        'shadowColor' => '#333',
        'borderColor' => '#000',
        'borderWidth' => 2,
        'borderRadius' => 4,
    ]);
});

it('builds a line style with the dashed helper and curveness', function (): void {
    expect(LineStyle::make()->color('gradient')->width(3)->dashed()->curveness(0.5)->toArray())
        ->toEqual(['color' => 'gradient', 'width' => 3, 'type' => 'dashed', 'curveness' => 0.5]);
});

it('builds a label with a formatter marker and bold', function (): void {
    expect(Label::make()->show()->position('inside')->bold()->formatter(RawJs::make('(v)=>v'))->toArray())
        ->toEqual([
            'show' => true,
            'position' => 'inside',
            'fontWeight' => 'bold',
            'formatter' => ['__js__' => '(v)=>v'],
        ]);
});

it('builds a label with align, verticalAlign and a rich style map', function (): void {
    expect(
        Label::make()
            ->align('center')
            ->verticalAlign('middle')
            ->rich(['name' => ['color' => '#fff', 'fontSize' => 14]])
            ->toArray()
    )->toEqual([
        'align' => 'center',
        'verticalAlign' => 'middle',
        'rich' => ['name' => ['color' => '#fff', 'fontSize' => 14]],
    ]);
});

it('builds a candlestick item style with color0 and borderColor0', function (): void {
    expect(ItemStyle::make()->color0('#ef5350')->borderColor0('#ef5350')->toArray())
        ->toEqual(['color0' => '#ef5350', 'borderColor0' => '#ef5350']);
});

it('resolves a Filament color palette for color0 and borderColor0', function (): void {
    expect(ItemStyle::make()->color0([500 => 'base'])->toArray())->toEqual(['color0' => 'base']);
    expect(ItemStyle::make()->borderColor0([500 => 'base'])->toArray())->toEqual(['borderColor0' => 'base']);
});

it('composes item/line/label styles inside an emphasis', function (): void {
    expect(
        Emphasis::make()
            ->focus('adjacency')
            ->itemStyle(ItemStyle::make()->color('#f00'))
            ->lineStyle(LineStyle::make()->width(2))
            ->label(Label::make()->show())
            ->toArray()
    )->toEqual([
        'focus' => 'adjacency',
        'itemStyle' => ['color' => '#f00'],
        'lineStyle' => ['width' => 2],
        'label' => ['show' => true],
    ]);
});

it('accepts a plain array for an emphasis sub-style', function (): void {
    expect(Emphasis::make()->itemStyle(['color' => '#fff'])->toArray())
        ->toEqual(['itemStyle' => ['color' => '#fff']]);
});

it('disables the emphasis state via the builder', function (): void {
    expect(Emphasis::make()->disabled()->toArray())->toEqual(['disabled' => true]);
});

it('sets emphasis on any series: false disables, true enables, a builder configures', function (): void {
    expect(BarSeries::make()->data([1])->emphasis(false)->toArray()['emphasis'])
        ->toEqual(['disabled' => true]);
    expect(BarSeries::make()->data([1])->emphasis(true)->toArray()['emphasis'])
        ->toEqual(['disabled' => false]);
    expect(BarSeries::make()->data([1])->emphasis(Emphasis::make()->focus('series'))->toArray()['emphasis'])
        ->toEqual(['focus' => 'series']);
});

it('lets ->raw() override a style key', function (): void {
    expect(ItemStyle::make()->color('#f00')->raw(['color' => '#0f0'])->toArray())
        ->toEqual(['color' => '#0f0']);
});

it('a style setter accepts both a builder and a plain array', function (): void {
    $viaBuilder = BarSeries::make()->itemStyle(ItemStyle::make()->color('#22c55e')->borderRadius(4))->data([1])->toArray();
    $viaArray = BarSeries::make()->itemStyle(['color' => '#22c55e', 'borderRadius' => 4])->data([1])->toArray();

    expect($viaBuilder)->toEqual($viaArray)
        ->and($viaBuilder['itemStyle'])->toEqual(['color' => '#22c55e', 'borderRadius' => 4]);
});

it('resolves a Filament color palette to its 500 shade, passes a string through', function (): void {
    $palette = [50 => 'light', 500 => 'base', 600 => 'dark'];

    expect(ItemStyle::make()->color($palette)->toArray())->toEqual(['color' => 'base']);
    expect(ItemStyle::make()->color('#fff')->toArray())->toEqual(['color' => '#fff']);
});

it('accepts a real Filament Color palette, converting its oklch shade to rgb', function (): void {
    // Filament v4 palettes are oklch(…) strings; ECharts can paint them but
    // can't derive an emphasis/hover shade from oklch (bars vanish on hover),
    // so color() converts the shade to the rgb ECharts can manipulate.
    expect(ItemStyle::make()->color(Color::Amber)->toArray())
        ->toEqual(['color' => Color::convertToRgb(Color::Amber[500])]);
});

it('resolves a Filament color palette for borderColor', function (): void {
    expect(ItemStyle::make()->borderColor([500 => 'base'])->toArray())->toEqual(['borderColor' => 'base']);
    expect(ItemStyle::make()->borderColor('#000')->toArray())->toEqual(['borderColor' => '#000']);
});

it('resolves a Filament color palette for shadow color', function (): void {
    expect(ItemStyle::make()->shadow(6, [500 => 'base'])->toArray())
        ->toEqual(['shadowBlur' => 6, 'shadowColor' => 'base']);
    expect(LineStyle::make()->shadow(6, '#333')->toArray())
        ->toEqual(['shadowBlur' => 6, 'shadowColor' => '#333']);
});

it('sets shadow offset and a standalone shadow color from the Style base', function (): void {
    expect(ItemStyle::make()->shadowOffsetX(2)->shadowOffsetY(3)->toArray())
        ->toEqual(['shadowOffsetX' => 2, 'shadowOffsetY' => 3]);
    expect(LineStyle::make()->shadowColor([500 => 'base'])->toArray())
        ->toEqual(['shadowColor' => 'base']);
});

it('builds an item style with areaColor, borderDashOffset and a decal pattern', function (): void {
    expect(
        ItemStyle::make()
            ->areaColor('#22c55e')
            ->borderDashOffset(5)
            ->decal(['symbol' => 'rect', 'color' => '#fff'])
            ->toArray()
    )->toEqual([
        'areaColor' => '#22c55e',
        'borderDashOffset' => 5,
        'decal' => ['symbol' => 'rect', 'color' => '#fff'],
    ]);
});

it('resolves a Filament color palette for areaColor', function (): void {
    expect(ItemStyle::make()->areaColor([500 => 'base'])->toArray())->toEqual(['areaColor' => 'base']);
});

it('builds a label with background, border, padding, box size and text-border styling', function (): void {
    expect(
        Label::make()
            ->backgroundColor('#000')
            ->borderColor('#333')
            ->borderWidth(1)
            ->borderRadius(4)
            ->borderType('dashed')
            ->padding([4, 8])
            ->width(100)
            ->height(20)
            ->lineHeight(18)
            ->offset([0, -10])
            ->textBorderColor('#fff')
            ->textBorderWidth(1)
            ->overflow('truncate')
            ->minMargin(2)
            ->shadowBlur(4)
            ->shadowColor('#111')
            ->toArray()
    )->toEqual([
        'backgroundColor' => '#000',
        'borderColor' => '#333',
        'borderWidth' => 1,
        'borderRadius' => 4,
        'borderType' => 'dashed',
        'padding' => [4, 8],
        'width' => 100,
        'height' => 20,
        'lineHeight' => 18,
        'offset' => [0, -10],
        'textBorderColor' => '#fff',
        'textBorderWidth' => 1,
        'overflow' => 'truncate',
        'minMargin' => 2,
        'shadowBlur' => 4,
        'shadowColor' => '#111',
    ]);
});

it('resolves a Filament color palette for label backgroundColor, borderColor and textBorderColor', function (): void {
    expect(Label::make()->backgroundColor([500 => 'base'])->toArray())->toEqual(['backgroundColor' => 'base']);
    expect(Label::make()->borderColor([500 => 'base'])->toArray())->toEqual(['borderColor' => 'base']);
    expect(Label::make()->textBorderColor([500 => 'base'])->toArray())->toEqual(['textBorderColor' => 'base']);
});

it('builds a line style with cap, join and dashOffset', function (): void {
    expect(LineStyle::make()->cap('round')->join('bevel')->dashOffset(2)->toArray())
        ->toEqual(['cap' => 'round', 'join' => 'bevel', 'dashOffset' => 2]);
});

it('builds an area style with origin and shadowBlur', function (): void {
    expect(AreaStyle::make()->origin('start')->shadowBlur(8)->toArray())
        ->toEqual(['origin' => 'start', 'shadowBlur' => 8]);
});

it('composes an areaStyle inside an emphasis', function (): void {
    expect(Emphasis::make()->areaStyle(AreaStyle::make()->color('#f00'))->toArray())
        ->toEqual(['areaStyle' => ['color' => '#f00']]);
});

it('defaults emphasis scale() to true', function (): void {
    expect(Emphasis::make()->scale()->toArray())->toEqual(['scale' => true]);
    expect(Emphasis::make()->scale(false)->toArray())->toEqual(['scale' => false]);
});

it('defaults label silent() to true', function (): void {
    expect(Label::make()->silent()->toArray())->toEqual(['silent' => true]);
    expect(Label::make()->silent(false)->toArray())->toEqual(['silent' => false]);
});
