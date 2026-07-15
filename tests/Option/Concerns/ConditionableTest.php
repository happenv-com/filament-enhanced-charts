<?php

use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\BarSeries;

it('applies ->when() and skips a falsy branch on Option', function () {
    expect(
        Option::make()
            ->when(true, fn (Option $option) => $option->color('#000'))
            ->when(false, fn (Option $option) => $option->color('#fff'))
            ->toArray()
    )->toBe(['color' => ['#000']]);
});

it('applies ->when() on a series builder', function () {
    expect(
        BarSeries::make()
            ->when(true, fn (BarSeries $series) => $series->name('x'))
            ->data([1])
            ->toArray()
    )->toBe(['type' => 'bar', 'name' => 'x', 'data' => [1]]);
});
