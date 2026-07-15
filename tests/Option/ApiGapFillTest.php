<?php

use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Enums\Focus;
use Happenv\FilamentEnhancedCharts\Enums\Symbol;
use Happenv\FilamentEnhancedCharts\Option\Axis\CategoryAxis;
use Happenv\FilamentEnhancedCharts\Option\Axis\ValueAxis;
use Happenv\FilamentEnhancedCharts\Option\Component\AxisPointer;
use Happenv\FilamentEnhancedCharts\Option\Component\DataZoom;
use Happenv\FilamentEnhancedCharts\Option\Component\Legend;
use Happenv\FilamentEnhancedCharts\Option\Component\VisualMap;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\BarSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\FunnelSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\LineSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\ScatterSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;

it('gives every series a typed label() via the base', function () {
    expect(BarSeries::make()->data([1])->label(Label::make()->show()->position('top'))->toArray()['label'])
        ->toEqual(['show' => true, 'position' => 'top']);

    expect(BarSeries::make()->data([1])->label(['formatter' => '{c}'])->toArray()['label'])
        ->toEqual(['formatter' => '{c}']);
});

it('adds boundaryGap/scale/inverse to axes', function () {
    expect(CategoryAxis::make()->boundaryGap(false)->inverse()->toArray())
        ->toEqual(['type' => 'category', 'boundaryGap' => false, 'inverse' => true]);

    expect(ValueAxis::make()->scale()->boundaryGap(['10%', '20%'])->toArray())
        ->toEqual(['type' => 'value', 'boundaryGap' => ['10%', '20%'], 'scale' => true]);
});

it('adds explicit data() items to a legend', function () {
    expect(Legend::make()->data(['Sales', 'Costs'])->toArray())
        ->toEqual(['data' => ['Sales', 'Costs']]);
});

it('adds axisLine to an axis and matrixIndex to a series', function () {
    expect(CategoryAxis::make()->axisLine(false)->toArray())
        ->toEqual(['type' => 'category', 'axisLine' => ['show' => false]]);

    expect(BarSeries::make()->coordinateSystem('matrix')->matrixIndex(1)->coord([0, 2])->data([1])->toArray())
        ->toMatchArray(['coordinateSystem' => 'matrix', 'matrixIndex' => 1, 'coord' => [0, 2]]);
});

it('positions a dataZoom via layout + height/width', function () {
    expect(DataZoom::slider()->bottom(10)->height(24)->left('10%')->toArray())
        ->toEqual(['type' => 'slider', 'bottom' => 10, 'left' => '10%', 'height' => 24]);
});

it('exposes hierarchical Focus and the EmptyCircle symbol', function () {
    expect(Focus::Descendant->value)->toBe('descendant')
        ->and(Focus::Ancestor->value)->toBe('ancestor')
        ->and(Symbol::EmptyCircle->value)->toBe('emptyCircle');
});

it('fills the last common gaps', function () {
    expect(Option::make()->backgroundColor('#111827')->toArray())
        ->toEqual(['backgroundColor' => '#111827']);

    expect(Legend::make()->textStyle(['color' => '#fff'])->selectedMode('single')->itemGap(20)->toArray())
        ->toEqual(['textStyle' => ['color' => '#fff'], 'selectedMode' => 'single', 'itemGap' => 20]);

    expect(VisualMap::continuous()->show(false)->toArray())
        ->toEqual(['type' => 'continuous', 'show' => false]);

    expect(CategoryAxis::make()->axisTick(false)->toArray())
        ->toEqual(['type' => 'category', 'axisTick' => ['show' => false]]);

    expect(LineSeries::make()->data([1])->showSymbol(false)->toArray()['showSymbol'])->toBeFalse();

    expect(FunnelSeries::make()->data([1])->width('40%')->funnelAlign('left')->toArray())
        ->toMatchArray(['width' => '40%', 'funnelAlign' => 'left']);
});

it('accepts keyword rotate and pie alignment options on Label', function () {
    expect(
        Label::make()->rotate('radial')->alignTo('edge')->edgeDistance(10)->bleedMargin(5)->distanceToLabelLine(4)->toArray()
    )->toEqual([
        'rotate' => 'radial',
        'alignTo' => 'edge',
        'edgeDistance' => 10,
        'bleedMargin' => 5,
        'distanceToLabelLine' => 4,
    ]);
});

it('accepts a RawJs symbolSize callback', function () {
    expect(
        ScatterSeries::make()
            ->symbolSize(RawJs::make('(d) => Math.sqrt(d[2])'))
            ->data([[1, 2, 9]])
            ->toArray()['symbolSize']
    )->toEqual(['__js__' => '(d) => Math.sqrt(d[2])']);
});

it('exposes jitter controls on cartesian axes', function () {
    expect(ValueAxis::make()->jitter(30)->jitterOverlap(false)->jitterMargin(2)->toArray())
        ->toEqual(['type' => 'value', 'jitter' => 30, 'jitterOverlap' => false, 'jitterMargin' => 2]);
});

it('links axis pointers across grids', function () {
    expect(
        AxisPointer::make()->link([['xAxisIndex' => 'all']])->toArray()
    )->toEqual(['link' => [['xAxisIndex' => 'all']]]);
});
