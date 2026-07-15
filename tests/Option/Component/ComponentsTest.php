<?php

use BcMath\Number;
use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Enums\Orient;
use Happenv\FilamentEnhancedCharts\Option\Component\Grid;
use Happenv\FilamentEnhancedCharts\Option\Component\Legend;
use Happenv\FilamentEnhancedCharts\Option\Component\Tooltip;
use Happenv\FilamentEnhancedCharts\Option\Component\VisualMap;

it('builds legend, grid and tooltip with a formatter marker', function () {
    expect(Legend::make()->top(0)->toArray())->toBe(['top' => 0]);
    expect(Grid::make()->containLabel()->top(32)->toArray())->toBe(['containLabel' => true, 'top' => 32]);
    expect(Tooltip::make()->trigger('axis')->valueFormatter('(v)=>v')->toArray())
        ->toBe(['trigger' => 'axis', 'valueFormatter' => ['__js__' => '(v)=>v']]);
});

it('builds a piecewise visual map', function () {
    expect(VisualMap::piecewise()->pieces([['min' => 0, 'max' => 5]])->toArray())
        ->toBe(['type' => 'piecewise', 'pieces' => [['min' => 0, 'max' => 5]]]);
});

it('builds a legend with bottom and show false', function () {
    expect(Legend::make()->bottom(0)->show(false)->toArray())
        ->toBe(['show' => false, 'bottom' => 0]);
});

it('defaults ->show() to true', function () {
    expect(Legend::make()->show()->toArray())->toBe(['show' => true]);
});

it('builds a grid with left, right and bottom margins', function () {
    expect(Grid::make()->left(8)->right(24)->bottom(40)->toArray())
        ->toBe(['left' => 8, 'right' => 24, 'bottom' => 40]);
});

it('builds a tooltip that confines to the chart area', function () {
    expect(Tooltip::make()->confine()->toArray())->toBe(['confine' => true]);
});

it('treats a bare string tooltip formatter as a literal ECharts template', function () {
    expect(Tooltip::make()->formatter('{b}: {c}')->toArray())
        ->toBe(['formatter' => '{b}: {c}']);
});

it('wraps a RawJs tooltip formatter in a js marker', function () {
    expect(Tooltip::make()->formatter(RawJs::make('(p)=>p.name'))->toArray())
        ->toBe(['formatter' => ['__js__' => '(p)=>p.name']]);
});

it('builds a continuous visual map with normalized min, calculable and orient', function () {
    expect(
        VisualMap::continuous()->min(new Number('0.50'))->max(10)->calculable()->orient('horizontal')->toArray()
    )->toBe([
        'type' => 'continuous',
        'min' => ['__js__' => '0.5'],
        'max' => 10,
        'calculable' => true,
        'orient' => 'horizontal',
    ]);
});

it('applies dimension on a visual map', function () {
    expect(VisualMap::continuous()->dimension(2)->toArray())
        ->toBe(['type' => 'continuous', 'dimension' => 2]);
});

it('builds a legend with a vertical orient docked to the right', function () {
    expect(Legend::make()->orient(Orient::Vertical)->right(0)->toArray())
        ->toBe(['orient' => 'vertical', 'right' => 0]);
});

it('lets raw() override any typed key on legend', function () {
    expect(Legend::make()->top(0)->raw(['top' => 5, 'orient' => 'vertical'])->toArray())
        ->toBe(['top' => 5, 'orient' => 'vertical']);
});

it('lets raw() override any typed key on tooltip', function () {
    expect(Tooltip::make()->trigger('axis')->raw(['trigger' => 'item'])->toArray())
        ->toBe(['trigger' => 'item']);
});

it('lets raw() override any typed key on grid', function () {
    expect(Grid::make()->containLabel()->raw(['containLabel' => false])->toArray())
        ->toBe(['containLabel' => false]);
});

it('lets raw() override any typed key on visual map', function () {
    expect(VisualMap::piecewise()->raw(['type' => 'continuous'])->toArray())
        ->toBe(['type' => 'continuous']);
});

it('builds a tooltip with an axis pointer configuration', function () {
    expect(
        Tooltip::make()->axisPointer(['type' => 'cross', 'label' => ['backgroundColor' => '#333']])->toArray()
    )->toEqual([
        'axisPointer' => ['type' => 'cross', 'label' => ['backgroundColor' => '#333']],
    ]);
});

it('builds a tooltip with background, border, padding and triggerOn', function () {
    expect(
        Tooltip::make()
            ->backgroundColor('#fff')
            ->borderColor('#000')
            ->borderWidth(1)
            ->padding([4, 8])
            ->triggerOn('click')
            ->toArray()
    )->toEqual([
        'backgroundColor' => '#fff',
        'borderColor' => '#000',
        'borderWidth' => 1,
        'padding' => [4, 8],
        'triggerOn' => 'click',
    ]);
});

it('resolves a Filament color palette for tooltip background and border', function () {
    expect(Tooltip::make()->backgroundColor([500 => 'base'])->toArray())
        ->toEqual(['backgroundColor' => 'base']);
    expect(Tooltip::make()->borderColor([500 => 'base'])->toArray())
        ->toEqual(['borderColor' => 'base']);
});

it('builds a tooltip with a string and an array position', function () {
    expect(Tooltip::make()->position('top')->toArray())->toEqual(['position' => 'top']);
    expect(Tooltip::make()->position([10, 20])->toArray())->toEqual(['position' => [10, 20]]);
});

it('lets raw() override a tooltip style key', function () {
    expect(
        Tooltip::make()->backgroundColor('#fff')->raw(['backgroundColor' => '#000'])->toArray()
    )->toEqual(['backgroundColor' => '#000']);
});
