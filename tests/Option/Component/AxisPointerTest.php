<?php

use Happenv\FilamentEnhancedCharts\Enums\AxisPointerType;
use Happenv\FilamentEnhancedCharts\Option\Component\AxisPointer;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;
use Happenv\FilamentEnhancedCharts\Option\Style\LineStyle;

covers(AxisPointer::class);

it('builds show, snap and triggerTooltip flags', function () {
    expect(AxisPointer::make()->show()->snap()->triggerTooltip()->toArray())
        ->toEqual(['show' => true, 'snap' => true, 'triggerTooltip' => true]);
});

it('accepts an AxisPointerType enum or a raw string on type()', function () {
    expect(AxisPointer::make()->type(AxisPointerType::Cross)->toArray())
        ->toEqual(['type' => 'cross']);

    expect(AxisPointer::make()->type('shadow')->toArray())
        ->toEqual(['type' => 'shadow']);
});

it('normalizes a Label node or a plain array on label()', function () {
    expect(AxisPointer::make()->label(Label::make()->formatter('{value}'))->toArray())
        ->toEqual(['label' => ['formatter' => '{value}']]);

    expect(AxisPointer::make()->label(['backgroundColor' => '#333'])->toArray())
        ->toEqual(['label' => ['backgroundColor' => '#333']]);
});

it('normalizes a LineStyle node or a plain array on lineStyle()', function () {
    expect(AxisPointer::make()->lineStyle(LineStyle::make()->dashed()->width(2))->toArray())
        ->toEqual(['lineStyle' => ['type' => 'dashed', 'width' => 2]]);

    expect(AxisPointer::make()->lineStyle(['color' => '#999'])->toArray())
        ->toEqual(['lineStyle' => ['color' => '#999']]);
});

it('passes shadowStyle and handle through as plain arrays', function () {
    expect(AxisPointer::make()->shadowStyle(['color' => 'rgba(0,0,0,0.3)'])->toArray())
        ->toEqual(['shadowStyle' => ['color' => 'rgba(0,0,0,0.3)']]);

    expect(AxisPointer::make()->handle(['show' => true, 'size' => 45])->toArray())
        ->toEqual(['handle' => ['show' => true, 'size' => 45]]);
});

it('builds a fixed value and status', function () {
    expect(AxisPointer::make()->value(10)->status('show')->toArray())
        ->toEqual(['value' => 10, 'status' => 'show']);
});

it('lets raw() override a typed key', function () {
    expect(AxisPointer::make()->type('line')->raw(['type' => 'cross'])->toArray())
        ->toEqual(['type' => 'cross']);
});
