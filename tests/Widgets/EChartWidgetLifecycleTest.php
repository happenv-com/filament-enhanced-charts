<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Tests\Fixtures\Widgets\DeferredTestWidget;
use Happenv\FilamentEnhancedCharts\Tests\Fixtures\Widgets\PollableTestWidget;
use Happenv\FilamentEnhancedCharts\Widgets\EnhancedChartWidget;

use function Pest\Livewire\livewire;

covers(EnhancedChartWidget::class);

it('mounts a chart widget and renders it under Livewire', function () {
    livewire(PollableTestWidget::class)
        ->assertOk()
        ->assertChartSeriesCount(1)
        ->assertChartHasSeries('bar');
});

it('hashes the resolved options on mount', function () {
    livewire(PollableTestWidget::class)
        ->assertSet('optionsHash', fn (?string $hash): bool => filled($hash));
});

it('dispatches updateOptions only when the resolved options actually change', function () {
    livewire(PollableTestWidget::class)
        // Nothing changed since mount → the poll dirty-check short-circuits.
        ->call('updateOptions')
        ->assertNotDispatched('updateOptions')
        // The data point changed → a real update is pushed to the client.
        ->set('value', 99)
        ->call('updateOptions')
        ->assertDispatched('updateOptions');
});

it('suppresses updateOptions while the filter dropdown is open', function () {
    livewire(PollableTestWidget::class)
        ->set('dropdownOpen', true)
        ->set('value', 99)
        ->call('updateOptions')
        ->assertNotDispatched('updateOptions');
});

it('defers loading until loadWidget is called', function () {
    livewire(DeferredTestWidget::class)
        ->assertSet('readyToLoad', false)
        ->assertChartSeriesCount(0)
        ->call('loadWidget')
        ->assertSet('readyToLoad', true)
        ->assertChartSeriesCount(1)
        ->assertChartHasSeries('bar');
});
