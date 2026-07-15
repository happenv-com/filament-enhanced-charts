<?php

declare(strict_types=1);

use BcMath\Number;
use Happenv\FilamentEnhancedCharts\Enums\GraphLayout;
use Happenv\FilamentEnhancedCharts\Enums\Symbol;
use Happenv\FilamentEnhancedCharts\Option\Series\GraphSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\Force;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;

covers(GraphSeries::class);

it('builds a minimal graph series from data', function () {
    expect(
        GraphSeries::make()
            ->data([['name' => 'A'], ['name' => 'B']])
            ->toArray()
    )->toBe([
        'type' => 'graph',
        'data' => [['name' => 'A'], ['name' => 'B']],
    ]);
});

it('accepts a GraphLayout enum for layout()', function () {
    expect(
        GraphSeries::make()->layout(GraphLayout::Force)->data([['name' => 'A']])->toArray()
    )->toBe([
        'type' => 'graph',
        'data' => [['name' => 'A']],
        'layout' => 'force',
    ]);
});

it('accepts a raw string for layout()', function () {
    expect(
        GraphSeries::make()->layout('circular')->data([['name' => 'A']])->toArray()
    )->toBe([
        'type' => 'graph',
        'data' => [['name' => 'A']],
        'layout' => 'circular',
    ]);
});

it('applies roam as a bool or a string', function () {
    expect(GraphSeries::make()->roam()->data([['name' => 'A']])->toArray())
        ->toBe(['type' => 'graph', 'data' => [['name' => 'A']], 'roam' => true]);

    expect(GraphSeries::make()->roam('move')->data([['name' => 'A']])->toArray())
        ->toBe(['type' => 'graph', 'data' => [['name' => 'A']], 'roam' => 'move']);
});

it('applies draggable', function () {
    expect(GraphSeries::make()->draggable()->data([['name' => 'A']])->toArray())
        ->toBe(['type' => 'graph', 'data' => [['name' => 'A']], 'draggable' => true]);
});

it('builds links with a BcMath-normalized value', function () {
    expect(
        GraphSeries::make()
            ->data([['name' => 'A'], ['name' => 'B']])
            ->links([['source' => 'A', 'target' => 'B', 'value' => new Number('4.50')]])
            ->toArray()
    )->toBe([
        'type' => 'graph',
        'data' => [['name' => 'A'], ['name' => 'B']],
        'links' => [['source' => 'A', 'target' => 'B', 'value' => ['__js__' => '4.5']]],
    ]);
});

it('accepts edges() as an alias for links()', function () {
    expect(
        GraphSeries::make()
            ->data([['name' => 'A'], ['name' => 'B']])
            ->edges([['source' => 'A', 'target' => 'B']])
            ->toArray()['links']
    )->toBe([['source' => 'A', 'target' => 'B']]);
});

it('applies categories', function () {
    expect(
        GraphSeries::make()
            ->data([['name' => 'A', 'category' => 0]])
            ->categories([['name' => 'Group A']])
            ->toArray()
    )->toBe([
        'type' => 'graph',
        'data' => [['name' => 'A', 'category' => 0]],
        'categories' => [['name' => 'Group A']],
    ]);
});

it('applies a Force builder for force()', function () {
    expect(
        GraphSeries::make()
            ->data([['name' => 'A']])
            ->force(
                Force::make()
                    ->repulsion(100)
                    ->gravity(0.2)
                    ->edgeLength([50, 200])
                    ->layoutAnimation(false)
            )
            ->toArray()
    )->toBe([
        'type' => 'graph',
        'data' => [['name' => 'A']],
        'force' => [
            'repulsion' => 100,
            'gravity' => 0.2,
            'edgeLength' => [50, 200],
            'layoutAnimation' => false,
        ],
    ]);
});

it('applies edgeSymbol, edgeSymbolSize and symbolSize', function () {
    expect(
        GraphSeries::make()
            ->data([['name' => 'A']])
            ->edgeSymbol(['none', 'arrow'])
            ->edgeSymbolSize(10)
            ->symbolSize(20)
            ->toArray()
    )->toBe([
        'type' => 'graph',
        'data' => [['name' => 'A']],
        'edgeSymbol' => ['none', 'arrow'],
        'edgeSymbolSize' => 10,
        'symbolSize' => 20,
    ]);
});

it('applies symbol and a [width, height] symbolSize via the shared symbol concern', function () {
    expect(GraphSeries::make()->symbol(Symbol::Circle)->symbolSize([20, 10])->toArray())
        ->toBe(['type' => 'graph', 'symbol' => 'circle', 'symbolSize' => [20, 10]]);
});

it('applies lineStyle, label, edgeLabel and itemStyle from arrays', function () {
    expect(
        GraphSeries::make()
            ->data([['name' => 'A']])
            ->lineStyle(['color' => 'source'])
            ->label(['show' => true])
            ->edgeLabel(['show' => false])
            ->itemStyle(ItemStyle::make()->borderColor('#fff'))
            ->toArray()
    )->toBe([
        'type' => 'graph',
        'label' => ['show' => true],
        'itemStyle' => ['borderColor' => '#fff'],
        'data' => [['name' => 'A']],
        'lineStyle' => ['color' => 'source'],
        'edgeLabel' => ['show' => false],
    ]);
});

it('applies center and zoom', function () {
    expect(
        GraphSeries::make()
            ->data([['name' => 'A']])
            ->center(['50%', '50%'])
            ->zoom(1.5)
            ->toArray()
    )->toBe([
        'type' => 'graph',
        'data' => [['name' => 'A']],
        'center' => ['50%', '50%'],
        'zoom' => 1.5,
    ]);
});

it('lets raw() override a graph series type', function () {
    expect(
        GraphSeries::make()
            ->data([['name' => 'A']])
            ->raw(['type' => 'tree'])
            ->toArray()['type']
    )->toBe('tree');
});

it('applies left/right/top/bottom/width/height from HasLayout', function () {
    expect(
        GraphSeries::make()
            ->data([['name' => 'A']])
            ->left('5%')
            ->top(10)
            ->width('80%')
            ->height(300)
            ->toArray()
    )->toEqual([
        'type' => 'graph',
        'data' => [['name' => 'A']],
        'left' => '5%',
        'top' => 10,
        'width' => '80%',
        'height' => 300,
    ]);
});

it('still resolves its own layout(GraphLayout|string) setter alongside HasLayout', function () {
    expect(
        GraphSeries::make()
            ->data([['name' => 'A']])
            ->layout(GraphLayout::Circular)
            ->left('10%')
            ->toArray()
    )->toEqual([
        'type' => 'graph',
        'data' => [['name' => 'A']],
        'layout' => 'circular',
        'left' => '10%',
    ]);
});

it('applies roamTrigger and scaleLimit', function () {
    expect(
        GraphSeries::make()
            ->data([['name' => 'A']])
            ->roamTrigger('global')
            ->scaleLimit(['min' => 0.5, 'max' => 2])
            ->toArray()
    )->toEqual([
        'type' => 'graph',
        'data' => [['name' => 'A']],
        'roamTrigger' => 'global',
        'scaleLimit' => ['min' => 0.5, 'max' => 2],
    ]);
});

it('accepts circular as a bool or a full config array', function () {
    expect(GraphSeries::make()->data([['name' => 'A']])->circular()->toArray()['circular'])
        ->toEqual(['rotateLabel' => true]);

    expect(GraphSeries::make()->data([['name' => 'A']])->circular(false)->toArray()['circular'])
        ->toEqual(['rotateLabel' => false]);

    expect(GraphSeries::make()->data([['name' => 'A']])->circular(['rotateLabel' => true])->toArray()['circular'])
        ->toEqual(['rotateLabel' => true]);
});

it('applies nodeScaleRatio', function () {
    expect(GraphSeries::make()->data([['name' => 'A']])->nodeScaleRatio(0.6)->toArray()['nodeScaleRatio'])
        ->toBe(0.6);
});
