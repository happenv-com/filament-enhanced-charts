<?php

use BcMath\Number;
use Happenv\FilamentEnhancedCharts\Option\SankeyLink;
use Happenv\FilamentEnhancedCharts\Option\SankeyNode;
use Happenv\FilamentEnhancedCharts\Option\Series\CustomSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\HeatmapSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\SankeySeries;
use Happenv\FilamentEnhancedCharts\Option\Series\TreemapSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;
use Happenv\FilamentEnhancedCharts\Option\Style\LineStyle;

it('builds a sankey with nodes and BcMath-normalized links', function () {
    expect(
        SankeySeries::make()
            ->nodeAlign('left')
            ->nodes([['name' => 'A'], ['name' => 'B']])
            ->links([['source' => 'A', 'target' => 'B', 'value' => new Number('12.50')]])
            ->toArray()
    )->toBe([
        'type' => 'sankey',
        'data' => [['name' => 'A'], ['name' => 'B']],
        'links' => [['source' => 'A', 'target' => 'B', 'value' => ['__js__' => '12.5']]],
        'nodeAlign' => 'left',
    ]);
});

it('applies lineStyle and emphasis on a sankey series', function () {
    expect(
        SankeySeries::make()
            ->lineStyle(['color' => 'gradient'])
            ->emphasis(['focus' => 'adjacency'])
            ->nodes([['name' => 'A']])
            ->toArray()
    )->toBe([
        'type' => 'sankey',
        'emphasis' => ['focus' => 'adjacency'],
        'data' => [['name' => 'A']],
        'lineStyle' => ['color' => 'gradient'],
    ]);
});

it('emits base-series options (id/z/silent/animation) on a sankey series', function () {
    // Regression: SankeySeries::build() used to bypass parent::build(), turning
    // every inherited setter into a silent no-op.
    $series = SankeySeries::make()
        ->id('flows')
        ->z(5)
        ->silent()
        ->animationDuration(300)
        ->links([['source' => 'A', 'target' => 'B', 'value' => 1]])
        ->toArray();

    expect($series)
        ->toHaveKey('id', 'flows')
        ->toHaveKey('z', 5)
        ->toHaveKey('silent', true)
        ->toHaveKey('animationDuration', 300)
        ->toHaveKey('links');
});

it('has no data() method on a sankey series (uses nodes()/links() instead)', function () {
    SankeySeries::make()->data([1]);
})->throws(Error::class, 'Call to undefined method');

it('builds a heatmap from triples', function () {
    expect(HeatmapSeries::make()->data([[0, 0, new Number('3')]])->toArray())
        ->toBe(['type' => 'heatmap', 'data' => [[0, 0, ['__js__' => '3']]]]);
});

it('applies itemStyle on a heatmap series', function () {
    expect(HeatmapSeries::make()->itemStyle(['borderColor' => '#fff'])->data([[0, 0, 1]])->toArray())
        ->toBe(['type' => 'heatmap', 'itemStyle' => ['borderColor' => '#fff'], 'data' => [[0, 0, 1]]]);
});

it('lets raw() override a heatmap series type', function () {
    expect(HeatmapSeries::make()->raw(['type' => 'scatter'])->toArray()['type'])
        ->toBe('scatter');
});

it('builds a treemap with nested BcMath values', function () {
    expect(TreemapSeries::make()->data([['name' => 'A', 'value' => new Number('5.00')]])->toArray())
        ->toBe(['type' => 'treemap', 'data' => [['name' => 'A', 'value' => ['__js__' => '5']]]]);
});

it('applies roam and leafDepth on a treemap series', function () {
    expect(TreemapSeries::make()->roam()->leafDepth(2)->data([['name' => 'A']])->toArray())
        ->toBe(['type' => 'treemap', 'data' => [['name' => 'A']], 'roam' => true, 'leafDepth' => 2]);
});

it('applies itemStyle on a treemap series', function () {
    $viaBuilder = TreemapSeries::make()->itemStyle(ItemStyle::make()->borderWidth(0))->data([['name' => 'A']])->toArray();
    $viaArray = TreemapSeries::make()->itemStyle(['borderWidth' => 0])->data([['name' => 'A']])->toArray();

    expect($viaBuilder)->toEqual([
        'type' => 'treemap',
        'data' => [['name' => 'A']],
        'itemStyle' => ['borderWidth' => 0],
    ]);
    expect($viaArray)->toBe($viaBuilder);
});

it('applies levels on a treemap series', function () {
    expect(
        TreemapSeries::make()
            ->levels([['itemStyle' => ['borderWidth' => 0, 'gapWidth' => 1]], ['itemStyle' => ['gapWidth' => 1]]])
            ->data([['name' => 'A']])
            ->toArray()
    )->toEqual([
        'type' => 'treemap',
        'data' => [['name' => 'A']],
        'levels' => [['itemStyle' => ['borderWidth' => 0, 'gapWidth' => 1]], ['itemStyle' => ['gapWidth' => 1]]],
    ]);
});

it('normalizes a BcMath value inside treemap levels to a marker', function () {
    expect(TreemapSeries::make()->levels([['visualMin' => new Number('1.50')]])->toArray()['levels'])
        ->toEqual([['visualMin' => ['__js__' => '1.5']]]);
});

it('applies visualMin, visualMax, visualDimension and colorMappingBy on a treemap series', function () {
    expect(
        TreemapSeries::make()
            ->visualMin(0)
            ->visualMax(100)
            ->visualDimension(1)
            ->colorMappingBy('value')
            ->data([['name' => 'A']])
            ->toArray()
    )->toEqual([
        'type' => 'treemap',
        'data' => [['name' => 'A']],
        'visualMin' => 0,
        'visualMax' => 100,
        'visualDimension' => 1,
        'colorMappingBy' => 'value',
    ]);
});

it('normalizes a BcMath visualMin/visualMax on a treemap series', function () {
    expect(TreemapSeries::make()->visualMin(new Number('1.50'))->visualMax(new Number('9.00'))->toArray())
        ->toEqual(['type' => 'treemap', 'visualMin' => ['__js__' => '1.5'], 'visualMax' => ['__js__' => '9']]);
});

it('accepts breadcrumb as a boolean or an array on a treemap series', function () {
    expect(TreemapSeries::make()->breadcrumb(false)->toArray()['breadcrumb'])->toBe(['show' => false]);
    expect(TreemapSeries::make()->breadcrumb(['height' => 30])->toArray()['breadcrumb'])->toBe(['height' => 30]);
});

it('builds a custom series with a renderItem marker', function () {
    expect(CustomSeries::make()->renderItem('(p,a)=>({})')->data([1])->toArray())
        ->toBe(['type' => 'custom', 'data' => [1], 'renderItem' => ['__js__' => '(p,a)=>({})']]);
});

it('applies encode on a custom series', function () {
    expect(CustomSeries::make()->encode(['x' => 0])->data([1])->toArray())
        ->toBe(['type' => 'custom', 'encode' => ['x' => 0], 'data' => [1]]);
});

it('applies itemStyle on a custom series (no ->raw needed)', function () {
    expect(CustomSeries::make()->itemStyle(['color' => '#6366f1'])->data([1])->toArray())
        ->toBe(['type' => 'custom', 'itemStyle' => ['color' => '#6366f1'], 'data' => [1]]);
});

it('accepts SankeyNode and SankeyLink objects in nodes()/links()', function () {
    expect(
        SankeySeries::make()
            ->nodes([SankeyNode::make('A'), SankeyNode::make('B')->value(10)])
            ->links([SankeyLink::make('A', 'B', new Number('12.50'))->lineStyle(LineStyle::make()->curveness(0.5))])
            ->toArray()
    )->toBe([
        'type' => 'sankey',
        'data' => [['name' => 'A'], ['name' => 'B', 'value' => 10]],
        'links' => [['source' => 'A', 'target' => 'B', 'value' => ['__js__' => '12.5'], 'lineStyle' => ['curveness' => 0.5]]],
    ]);
});

it('derives sankey nodes from links when nodes() is omitted', function () {
    expect(
        SankeySeries::make()->links([
            SankeyLink::make('A', 'B', 5),
            SankeyLink::make('A', 'C', 3),
        ])->toArray()
    )->toBe([
        'type' => 'sankey',
        'data' => [['name' => 'A'], ['name' => 'B'], ['name' => 'C']],
        'links' => [
            ['source' => 'A', 'target' => 'B', 'value' => 5],
            ['source' => 'A', 'target' => 'C', 'value' => 3],
        ],
    ]);
});

it('overlays explicit SankeyNode config onto derived nodes by name', function () {
    expect(
        SankeySeries::make()
            ->nodes([SankeyNode::make('B')->itemStyle(['color' => '#f00'])])
            ->links([SankeyLink::make('A', 'B', 5)])
            ->toArray()['data']
    )->toBe([
        ['name' => 'A'],
        ['name' => 'B', 'itemStyle' => ['color' => '#f00']],
    ]);
});
