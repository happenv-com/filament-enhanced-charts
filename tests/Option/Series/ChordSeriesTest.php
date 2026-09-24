<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\SankeyLink;
use Happenv\FilamentEnhancedCharts\Option\SankeyNode;
use Happenv\FilamentEnhancedCharts\Option\Series\ChordSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;
use Happenv\FilamentEnhancedCharts\Option\Style\LineStyle;

covers(ChordSeries::class);

it('derives the nodes from the links', function (): void {
    expect(ChordSeries::make()->links([
        ['source' => 'A', 'target' => 'B', 'value' => 5],
        ['source' => 'B', 'target' => 'C', 'value' => 2],
    ])->toArray())->toEqual([
        'type' => 'chord',
        'data' => [['name' => 'A'], ['name' => 'B'], ['name' => 'C']],
        'links' => [
            ['source' => 'A', 'target' => 'B', 'value' => 5],
            ['source' => 'B', 'target' => 'C', 'value' => 2],
        ],
    ]);
});

it('overlays configured nodes by name and keeps unlinked ones', function (): void {
    $series = ChordSeries::make()
        ->nodes([
            SankeyNode::make('B')->itemStyle(ItemStyle::make()->color('#f00')),
            ['name' => 'Lonely', 'value' => 3],
        ])
        ->links([SankeyLink::make('A', 'B', 4)])
        ->toArray();

    expect($series['data'])->toEqual([
        ['name' => 'A'],
        ['name' => 'B', 'itemStyle' => ['color' => '#f00']],
        ['name' => 'Lonely', 'value' => 3],
    ]);
});

it('applies the ring geometry', function (): void {
    expect(ChordSeries::make()
        ->radius(['62%', '72%'])
        ->center(['50%', '52%'])
        ->startAngle(90)
        ->endAngle('auto')
        ->clockwise(false)
        ->padAngle(4)
        ->minAngle(6)
        ->toArray())->toEqual([
            'type' => 'chord',
            'radius' => ['62%', '72%'],
            'center' => ['50%', '52%'],
            'clockwise' => false,
            'startAngle' => 90,
            'endAngle' => 'auto',
            'padAngle' => 4,
            'minAngle' => 6,
        ]);
});

it('styles the ribbons and their labels', function (): void {
    expect(ChordSeries::make()
        ->lineStyle(LineStyle::make()->color('gradient')->opacity(0.4))
        ->edgeLabel(Label::make()->show()->fontSize(10))
        ->toArray())->toEqual([
            'type' => 'chord',
            'lineStyle' => ['color' => 'gradient', 'opacity' => 0.4],
            'edgeLabel' => ['show' => true, 'fontSize' => 10],
        ]);

    expect(ChordSeries::make()->edgeLabel(['show' => false])->toArray()['edgeLabel'])->toBe(['show' => false]);
});

it('supports the shared series options and box layout', function (): void {
    expect(ChordSeries::make()->name('Trade')->left(10)->width('80%')->label(['position' => 'outside'])->toArray())
        ->toEqual([
            'type' => 'chord',
            'name' => 'Trade',
            'label' => ['position' => 'outside'],
            'width' => '80%',
            'left' => 10,
        ]);
});

it('lets raw() override any typed key', function (): void {
    expect(ChordSeries::make()->padAngle(3)->raw(['padAngle' => 1])->toArray()['padAngle'])->toBe(1);
});

it('slots into an option as a chord series', function (): void {
    expect(Option::make()->series(ChordSeries::make()->links([['source' => 'A', 'target' => 'B', 'value' => 1]]))->toArray()['series'][0]['type'])
        ->toBe('chord');
});
