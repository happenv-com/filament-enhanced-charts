<?php

use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\BarSeries;

it('applies ->when() and skips a falsy branch on Option', function (): void {
    expect(
        Option::make()
            ->when(true, fn (Option $option): Option => $option->color('#000'))
            ->when(false, fn (Option $option): Option => $option->color('#fff'))
            ->toArray()
    )->toBe(['color' => ['#000']]);
});

it('applies ->when() on a series builder', function (): void {
    expect(
        BarSeries::make()
            ->when(true, fn (BarSeries $series): BarSeries => $series->name('x'))
            ->data([1])
            ->toArray()
    )->toBe(['type' => 'bar', 'name' => 'x', 'data' => [1]]);
});
