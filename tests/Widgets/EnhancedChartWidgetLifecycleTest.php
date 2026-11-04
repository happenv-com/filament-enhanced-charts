<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Tests\Fixtures\Widgets\DeferredTestWidget;
use Happenv\FilamentEnhancedCharts\Tests\Fixtures\Widgets\PollableTestWidget;
use Happenv\FilamentEnhancedCharts\Widgets\EnhancedChartWidget;

use function Pest\Livewire\livewire;

covers(EnhancedChartWidget::class);

it('mounts a chart widget and renders it under Livewire', function (): void {
    livewire(PollableTestWidget::class)
        ->assertOk()
        ->assertChartSeriesCount(1)
        ->assertChartHasSeries('bar');
});

it('hashes the resolved options on mount', function (): void {
    livewire(PollableTestWidget::class)
        ->assertSet('optionsHash', fn (?string $hash): bool => filled($hash));
});

afterEach(function (): void {
    PollableTestWidget::$outside = 0;
});

it('dispatches updateOptions from a poll only when the resolved options actually change', function (): void {
    $widget = livewire(PollableTestWidget::class)
        // Nothing changed since mount → the poll dirty-check short-circuits.
        ->call('updateOptions')
        ->assertNotDispatched('updateOptions');

    // The data behind the chart changed → the next poll pushes it.
    PollableTestWidget::$outside = 10;

    $widget->call('updateOptions')
        ->assertDispatched('updateOptions', fn (string $name, array $params): bool => $params['options']['series'][0]['data'] === [11]);
});

it('pushes the options a re-render changed, once', function (): void {
    livewire(PollableTestWidget::class)
        ->set('value', 99)
        ->assertDispatched('updateOptions', fn (string $name, array $params): bool => $params['options']['series'][0]['data'] === [99])
        // The poll right after has nothing new to say.
        ->call('updateOptions')
        ->assertNotDispatched('updateOptions');
});

it('suppresses updateOptions while the filter dropdown is open, and delivers it once the dropdown closes', function (): void {
    $widget = livewire(PollableTestWidget::class)
        ->set('dropdownOpen', true);

    PollableTestWidget::$outside = 10;

    $widget->call('updateOptions')
        ->assertNotDispatched('updateOptions')
        ->set('dropdownOpen', false)
        ->assertDispatched('updateOptions', fn (string $name, array $params): bool => $params['options']['series'][0]['data'] === [11]);
});

it('keeps the chart host out of the Livewire morph', function (): void {
    // A morph that rewrote the options in `x-data` would make Alpine tear the
    // chart down, and the restored `x-ignore` would keep it from coming back.
    livewire(PollableTestWidget::class)
        ->assertSeeHtml('<div wire:ignore x-ignore x-load');
});

it('defers loading until loadWidget is called', function (): void {
    livewire(DeferredTestWidget::class)
        ->assertSet('readyToLoad', false)
        ->assertChartSeriesCount(0)
        ->call('loadWidget')
        ->assertSet('readyToLoad', true)
        ->assertChartSeriesCount(1)
        ->assertChartHasSeries('bar');
});
