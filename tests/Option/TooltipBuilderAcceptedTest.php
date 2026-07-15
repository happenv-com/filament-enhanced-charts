<?php

use Happenv\FilamentEnhancedCharts\Option\Component\Tooltip;
use Happenv\FilamentEnhancedCharts\Option\DataPoint;
use Happenv\FilamentEnhancedCharts\Option\Mark\MarkArea;
use Happenv\FilamentEnhancedCharts\Option\Mark\MarkLine;
use Happenv\FilamentEnhancedCharts\Option\Mark\MarkPoint;
use Happenv\FilamentEnhancedCharts\Option\Series\LineSeries;

it('accepts a Tooltip builder on a mark area', function () {
    $mark = MarkArea::make()->tooltip(Tooltip::make()->trigger('item'))->toArray();

    expect($mark['tooltip'])->toBe(['trigger' => 'item']);
});

it('accepts a Tooltip builder on a mark line', function () {
    $mark = MarkLine::make()->tooltip(Tooltip::make()->trigger('item'))->toArray();

    expect($mark['tooltip'])->toBe(['trigger' => 'item']);
});

it('accepts a Tooltip builder on a mark point', function () {
    $mark = MarkPoint::make()->tooltip(Tooltip::make()->trigger('item'))->toArray();

    expect($mark['tooltip'])->toBe(['trigger' => 'item']);
});

it('accepts a Tooltip builder on a series', function () {
    $series = LineSeries::make()->tooltip(Tooltip::make()->backgroundColor('#000'))->toArray();

    expect($series['tooltip'])->toBe(['backgroundColor' => '#000']);
});

it('accepts a Tooltip builder on a data point', function () {
    $point = DataPoint::make(1)->tooltip(Tooltip::make()->confine())->toArray();

    expect($point['tooltip'])->toBe(['confine' => true]);
});

it('still accepts a plain array for backward compatibility', function () {
    $mark = MarkArea::make()->tooltip(['formatter' => '{b}'])->toArray();

    expect($mark['tooltip'])->toBe(['formatter' => '{b}']);
});
