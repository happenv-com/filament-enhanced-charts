<?php

use Happenv\FilamentEnhancedCharts\Option\Series\FunnelSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\SankeySeries;
use Happenv\FilamentEnhancedCharts\Option\Series\TreemapSeries;

it('adds rectangular layout edges to a sankey series', function () {
    $series = SankeySeries::make()
        ->nodes([['name' => 'A']])
        ->left('1%')->right('9%')->top(6)->bottom('6%')
        ->toArray();

    expect($series)->toMatchArray(['left' => '1%', 'right' => '9%', 'top' => 6, 'bottom' => '6%']);
});

it('lets a sankey series set its label config (no ->raw needed)', function () {
    expect(SankeySeries::make()->nodes([['name' => 'A']])->label(['position' => 'left'])->toArray())
        ->toHaveKey('label', ['position' => 'left']);
});

it('adds layout edges to treemap and funnel series', function () {
    expect(TreemapSeries::make()->data([['name' => 'A']])->left(8)->right(8)->toArray())
        ->toMatchArray(['left' => 8, 'right' => 8]);

    expect(FunnelSeries::make()->data([['value' => 1]])->top('10%')->bottom('10%')->toArray())
        ->toMatchArray(['top' => '10%', 'bottom' => '10%']);
});

it('omits unset layout edges (keeps int 0)', function () {
    $series = TreemapSeries::make()->data([['name' => 'A']])->left(0)->toArray();

    expect($series)->toHaveKey('left', 0)
        ->and($series)->not->toHaveKey('right')
        ->and($series)->not->toHaveKey('top')
        ->and($series)->not->toHaveKey('bottom');
});

it('lets ->raw() still override a layout edge', function () {
    expect(TreemapSeries::make()->data([['name' => 'A']])->left('5%')->raw(['left' => '1%'])->toArray())
        ->toHaveKey('left', '1%');
});
