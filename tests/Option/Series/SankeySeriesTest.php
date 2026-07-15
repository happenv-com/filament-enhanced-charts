<?php

use Happenv\FilamentEnhancedCharts\Enums\NodeAlign;
use Happenv\FilamentEnhancedCharts\Enums\Orient;
use Happenv\FilamentEnhancedCharts\Option\Series\SankeySeries;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;

covers(SankeySeries::class);

it('derives nodes from links', function () {
    expect(
        SankeySeries::make()
            ->links([['source' => 'A', 'target' => 'B', 'value' => 5]])
            ->toArray()
    )->toEqual([
        'type' => 'sankey',
        'data' => [['name' => 'A'], ['name' => 'B']],
        'links' => [['source' => 'A', 'target' => 'B', 'value' => 5]],
    ]);
});

it('applies orient from an enum and a bare string', function () {
    expect(SankeySeries::make()->orient(Orient::Vertical)->toArray())
        ->toHaveKey('orient', 'vertical');

    expect(SankeySeries::make()->orient('horizontal')->toArray())
        ->toHaveKey('orient', 'horizontal');
});

it('applies nodeAlign, nodeGap and nodeWidth', function () {
    expect(
        SankeySeries::make()
            ->nodeAlign(NodeAlign::Left)
            ->nodeGap(10)
            ->nodeWidth(20)
            ->toArray()
    )->toEqual([
        'type' => 'sankey',
        'nodeAlign' => 'left',
        'nodeGap' => 10,
        'nodeWidth' => 20,
    ]);
});

it('applies layoutIterations and draggable', function () {
    expect(SankeySeries::make()->layoutIterations(64)->draggable(false)->toArray())
        ->toEqual([
            'type' => 'sankey',
            'layoutIterations' => 64,
            'draggable' => false,
        ]);
});

it('applies levels as a raw array', function () {
    expect(
        SankeySeries::make()
            ->levels([['depth' => 0, 'itemStyle' => ['color' => '#f00']]])
            ->toArray()
    )->toEqual([
        'type' => 'sankey',
        'levels' => [['depth' => 0, 'itemStyle' => ['color' => '#f00']]],
    ]);
});

it('applies edgeLabel and itemStyle', function () {
    expect(
        SankeySeries::make()
            ->edgeLabel(['show' => true])
            ->itemStyle(ItemStyle::make()->color('#00f'))
            ->toArray()
    )->toEqual([
        'type' => 'sankey',
        'edgeLabel' => ['show' => true],
        'itemStyle' => ['color' => '#00f'],
    ]);
});

it('applies width and height alongside HasLayout edges', function () {
    expect(
        SankeySeries::make()->width('80%')->height(300)->left('5%')->toArray()
    )->toEqual([
        'type' => 'sankey',
        'width' => '80%',
        'height' => 300,
        'left' => '5%',
    ]);
});

it('applies layout alongside HasLayout box positioning', function () {
    expect(
        SankeySeries::make()->layout('none')->left('5%')->toArray()
    )->toEqual([
        'type' => 'sankey',
        'layout' => 'none',
        'left' => '5%',
    ]);
});
