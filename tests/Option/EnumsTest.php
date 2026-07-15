<?php

use Happenv\FilamentEnhancedCharts\Enums\Focus;
use Happenv\FilamentEnhancedCharts\Enums\LineType;
use Happenv\FilamentEnhancedCharts\Enums\NodeAlign;
use Happenv\FilamentEnhancedCharts\Enums\Orient;
use Happenv\FilamentEnhancedCharts\Enums\RoseType;
use Happenv\FilamentEnhancedCharts\Enums\Sort;
use Happenv\FilamentEnhancedCharts\Enums\TooltipTrigger;
use Happenv\FilamentEnhancedCharts\Option\Component\Tooltip;
use Happenv\FilamentEnhancedCharts\Option\Component\VisualMap;
use Happenv\FilamentEnhancedCharts\Option\Series\FunnelSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\PieSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\SankeySeries;
use Happenv\FilamentEnhancedCharts\Option\Style\Emphasis;
use Happenv\FilamentEnhancedCharts\Option\Style\LineStyle;

it('accepts a TooltipTrigger enum or a raw string on Tooltip::trigger', function () {
    expect(Tooltip::make()->trigger(TooltipTrigger::Axis)->toArray())->toBe(['trigger' => 'axis']);
    expect(Tooltip::make()->trigger('item')->toArray())->toBe(['trigger' => 'item']);
});

it('accepts an Orient enum or a raw string on VisualMap::orient', function () {
    expect(VisualMap::continuous()->orient(Orient::Vertical)->toArray())
        ->toBe(['type' => 'continuous', 'orient' => 'vertical']);
    expect(VisualMap::continuous()->orient('horizontal')->toArray())
        ->toBe(['type' => 'continuous', 'orient' => 'horizontal']);
});

it('accepts a Sort enum or a raw string on FunnelSeries::sort', function () {
    expect(FunnelSeries::make()->sort(Sort::Ascending)->data([1])->toArray())
        ->toBe(['type' => 'funnel', 'data' => [1], 'sort' => 'ascending']);
    expect(FunnelSeries::make()->sort('descending')->data([1])->toArray())
        ->toBe(['type' => 'funnel', 'data' => [1], 'sort' => 'descending']);
});

it('accepts a NodeAlign enum or a raw string on SankeySeries::nodeAlign', function () {
    expect(SankeySeries::make()->nodeAlign(NodeAlign::Left)->links([['source' => 'A', 'target' => 'B']])->toArray()['nodeAlign'])
        ->toBe('left');
    expect(SankeySeries::make()->nodeAlign('right')->links([['source' => 'A', 'target' => 'B']])->toArray()['nodeAlign'])
        ->toBe('right');
});

it('accepts a RoseType enum or a raw string on PieSeries::roseType', function () {
    expect(PieSeries::make()->roseType(RoseType::Radius)->data([1])->toArray())
        ->toBe(['type' => 'pie', 'data' => [1], 'roseType' => 'radius']);
    expect(PieSeries::make()->roseType('area')->data([1])->toArray())
        ->toBe(['type' => 'pie', 'data' => [1], 'roseType' => 'area']);
});

it('accepts a LineType enum or a raw string on LineStyle::type', function () {
    expect(LineStyle::make()->type(LineType::Dashed)->toArray())->toBe(['type' => 'dashed']);
    expect(LineStyle::make()->type('dotted')->toArray())->toBe(['type' => 'dotted']);
});

it('keeps the dashed/dotted/solid helpers working after widening LineStyle::type', function () {
    expect(LineStyle::make()->dashed()->toArray())->toBe(['type' => 'dashed']);
    expect(LineStyle::make()->dotted()->toArray())->toBe(['type' => 'dotted']);
    expect(LineStyle::make()->solid()->toArray())->toBe(['type' => 'solid']);
});

it('accepts a Focus enum or a raw string on Emphasis::focus', function () {
    expect(Emphasis::make()->focus(Focus::Adjacency)->toArray())->toBe(['focus' => 'adjacency']);
    expect(Emphasis::make()->focus('self')->toArray())->toBe(['focus' => 'self']);
});
