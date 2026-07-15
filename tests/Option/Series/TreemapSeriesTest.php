<?php

use Happenv\FilamentEnhancedCharts\Option\Series\TreemapSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;

covers(TreemapSeries::class);

it('builds a treemap with hierarchical data', function () {
    expect(
        TreemapSeries::make()->data([
            ['name' => 'A', 'value' => 10, 'children' => [
                ['name' => 'B', 'value' => 4],
            ]],
        ])->toArray()
    )->toEqual([
        'type' => 'treemap',
        'data' => [
            ['name' => 'A', 'value' => 10, 'children' => [
                ['name' => 'B', 'value' => 4],
            ]],
        ],
    ]);
});

it('applies roam as a bool or a string mode', function () {
    expect(TreemapSeries::make()->roam(false)->toArray())
        ->toEqual(['type' => 'treemap', 'roam' => false]);

    expect(TreemapSeries::make()->roam('move')->toArray())
        ->toEqual(['type' => 'treemap', 'roam' => 'move']);
});

it('applies visibleMin and leafDepth', function () {
    expect(TreemapSeries::make()->visibleMin(20)->leafDepth(2)->toArray())
        ->toEqual(['type' => 'treemap', 'visibleMin' => 20, 'leafDepth' => 2]);
});

it('applies nodeClick as a bool or a string mode', function () {
    expect(TreemapSeries::make()->nodeClick(false)->toArray())
        ->toEqual(['type' => 'treemap', 'nodeClick' => false]);

    expect(TreemapSeries::make()->nodeClick('link')->toArray())
        ->toEqual(['type' => 'treemap', 'nodeClick' => 'link']);
});

it('applies breadcrumb as a false flag by default-true convention', function () {
    expect(TreemapSeries::make()->breadcrumb(false)->toArray())
        ->toEqual(['type' => 'treemap', 'breadcrumb' => ['show' => false]]);

    expect(TreemapSeries::make()->breadcrumb()->toArray())
        ->toEqual(['type' => 'treemap', 'breadcrumb' => ['show' => true]]);

    expect(TreemapSeries::make()->breadcrumb(['left' => 'center'])->toArray())
        ->toEqual(['type' => 'treemap', 'breadcrumb' => ['left' => 'center']]);
});

it('applies upperLabel as a bool or a config array', function () {
    expect(TreemapSeries::make()->upperLabel()->toArray())
        ->toEqual(['type' => 'treemap', 'upperLabel' => ['show' => true]]);

    expect(TreemapSeries::make()->upperLabel(['height' => 20])->toArray())
        ->toEqual(['type' => 'treemap', 'upperLabel' => ['height' => 20]]);
});

it('applies itemStyle as a builder and label as an array', function () {
    expect(
        TreemapSeries::make()
            ->itemStyle(ItemStyle::make()->color('#f00'))
            ->label(Label::make()->show())
            ->toArray()
    )->toEqual([
        'type' => 'treemap',
        'itemStyle' => ['color' => '#f00'],
        'label' => ['show' => true],
    ]);
});

it('applies colorMappingBy, visualDimension, visualMin and visualMax', function () {
    expect(
        TreemapSeries::make()
            ->colorMappingBy('value')
            ->visualDimension(1)
            ->visualMin(0)
            ->visualMax(100)
            ->toArray()
    )->toEqual([
        'type' => 'treemap',
        'colorMappingBy' => 'value',
        'visualDimension' => 1,
        'visualMin' => 0,
        'visualMax' => 100,
    ]);
});

it('applies levels as a raw array', function () {
    expect(
        TreemapSeries::make()->levels([['itemStyle' => ['borderWidth' => 0, 'gapWidth' => 1]]])->toArray()
    )->toEqual([
        'type' => 'treemap',
        'levels' => [['itemStyle' => ['borderWidth' => 0, 'gapWidth' => 1]]],
    ]);
});

it('applies width and height alongside HasLayout edges', function () {
    expect(TreemapSeries::make()->width('90%')->height('80%')->top(10)->toArray())
        ->toEqual([
            'type' => 'treemap',
            'width' => '90%',
            'height' => '80%',
            'top' => 10,
        ]);
});
