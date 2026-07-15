<?php

use BcMath\Number;
use Happenv\FilamentEnhancedCharts\Enums\Symbol;
use Happenv\FilamentEnhancedCharts\Enums\TreeEdgeShape;
use Happenv\FilamentEnhancedCharts\Enums\TreeLayout;
use Happenv\FilamentEnhancedCharts\Enums\TreeOrient;
use Happenv\FilamentEnhancedCharts\Option\Series\TreeSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;

covers(TreeSeries::class);

it('builds a tree with hierarchical BcMath-normalized data', function () {
    expect(
        TreeSeries::make()->data([
            ['name' => 'A', 'value' => new Number('5.00'), 'children' => [
                ['name' => 'B', 'value' => 3],
            ]],
        ])->toArray()
    )->toBe([
        'type' => 'tree',
        'data' => [
            ['name' => 'A', 'value' => ['__js__' => '5'], 'children' => [
                ['name' => 'B', 'value' => 3],
            ]],
        ],
    ]);
});

it('applies layout and orient from enums', function () {
    expect(
        TreeSeries::make()
            ->layout(TreeLayout::Radial)
            ->orient(TreeOrient::LeftRight)
            ->data([['name' => 'A']])
            ->toArray()
    )->toBe([
        'type' => 'tree',
        'data' => [['name' => 'A']],
        'layout' => 'radial',
        'orient' => 'LR',
    ]);
});

it('accepts layout and orient as bare strings', function () {
    expect(
        TreeSeries::make()
            ->layout('orthogonal')
            ->orient('TB')
            ->data([['name' => 'A']])
            ->toArray()
    )->toBe([
        'type' => 'tree',
        'data' => [['name' => 'A']],
        'layout' => 'orthogonal',
        'orient' => 'TB',
    ]);
});

it('applies edgeShape from an enum and a bare string', function () {
    expect(TreeSeries::make()->edgeShape(TreeEdgeShape::Polyline)->data([['name' => 'A']])->toArray())
        ->toHaveKey('edgeShape', 'polyline');

    expect(TreeSeries::make()->edgeShape('curve')->data([['name' => 'A']])->toArray())
        ->toHaveKey('edgeShape', 'curve');
});

it('applies roam, initialTreeDepth and leaves', function () {
    expect(
        TreeSeries::make()
            ->roam()
            ->initialTreeDepth(2)
            ->leaves(['label' => ['position' => 'right']])
            ->data([['name' => 'A']])
            ->toArray()
    )->toBe([
        'type' => 'tree',
        'data' => [['name' => 'A']],
        'roam' => true,
        'initialTreeDepth' => 2,
        'leaves' => ['label' => ['position' => 'right']],
    ]);
});

it('accepts a string roam mode', function () {
    expect(TreeSeries::make()->roam('move')->data([['name' => 'A']])->toArray())
        ->toHaveKey('roam', 'move');
});

it('applies symbol and symbolSize via HasSymbol', function () {
    expect(TreeSeries::make()->symbol(Symbol::Diamond)->symbolSize(8)->data([['name' => 'A']])->toArray())
        ->toBe([
            'type' => 'tree',
            'data' => [['name' => 'A']],
            'symbol' => 'diamond',
            'symbolSize' => 8,
        ]);
});

it('applies itemStyle as a builder and lineStyle as an array', function () {
    expect(
        TreeSeries::make()
            ->itemStyle(ItemStyle::make()->color('#f00'))
            ->lineStyle(['color' => '#ccc', 'curveness' => 0.5])
            ->data([['name' => 'A']])
            ->toArray()
    )->toBe([
        'type' => 'tree',
        'itemStyle' => ['color' => '#f00'],
        'data' => [['name' => 'A']],
        'lineStyle' => ['color' => '#ccc', 'curveness' => 0.5],
    ]);
});

it('applies expandAndCollapse and edgeForkPosition', function () {
    expect(
        TreeSeries::make()
            ->expandAndCollapse()
            ->edgeForkPosition('50%')
            ->data([['name' => 'A']])
            ->toArray()
    )->toEqual([
        'type' => 'tree',
        'data' => [['name' => 'A']],
        'expandAndCollapse' => true,
        'edgeForkPosition' => '50%',
    ]);
});

it('adds box-layout edges (HasLayout) alongside the tree layout algorithm', function () {
    expect(
        TreeSeries::make()->layout(TreeLayout::Radial)->left('5%')->top(10)->data([['name' => 'A']])->toArray()
    )->toMatchArray(['layout' => 'radial', 'left' => '5%', 'top' => 10]);
});

it('lets raw() override a typed key', function () {
    expect(
        TreeSeries::make()->layout(TreeLayout::Orthogonal)->raw(['layout' => 'radial'])->toArray()['layout']
    )->toBe('radial');
});
