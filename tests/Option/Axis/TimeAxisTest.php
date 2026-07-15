<?php

use Happenv\FilamentEnhancedCharts\Option\Axis\TimeAxis;

covers(TimeAxis::class);

it('builds a minimal time axis', function () {
    expect(TimeAxis::make()->toArray())->toEqual(['type' => 'time']);
});

it('inherits the base Axis setters', function () {
    expect(TimeAxis::make()->name('Date')->min('2026-01-01')->splitLine(false)->toArray())
        ->toEqual([
            'type' => 'time',
            'name' => 'Date',
            'min' => '2026-01-01',
            'splitLine' => ['show' => false],
        ]);
});

it('lets raw() override the time axis type', function () {
    expect(TimeAxis::make()->raw(['type' => 'value'])->toArray()['type'])->toBe('value');
});
