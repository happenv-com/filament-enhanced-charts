<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Concerns\CanFilter;
use Happenv\FilamentEnhancedCharts\Tests\Fixtures\Widgets\FilteredTestWidget;
use Happenv\FilamentEnhancedCharts\Tests\Fixtures\Widgets\SchemaFilteredTestWidget;

use function Pest\Livewire\livewire;

covers(CanFilter::class);

describe('single-select filter', function () {
    it('renders the filter options in the header', function () {
        livewire(FilteredTestWidget::class)
            ->assertSet('filter', 'week')
            ->assertSee('Last week')
            ->assertSee('Last month');
    });

    it('rebuilds the chart and pushes an update when the filter changes', function () {
        livewire(FilteredTestWidget::class)
            ->assertChartOptions(fn (array $options): bool => count($options['xAxis']['data']) === 7)
            ->set('filter', 'month')
            ->assertDispatched('updateOptions')
            ->assertChartOptions(fn (array $options): bool => count($options['xAxis']['data']) === 30);
    });
});

describe('filter schema', function () {
    it('fills the schema defaults into $filters on mount', function () {
        livewire(SchemaFilteredTestWidget::class)
            ->assertOk()
            ->assertSet('filters.range', 'short')
            ->assertChartOptions(fn (array $options): bool => $options['series'][0]['data'] === [1, 2]);
    });

    it('reads the active filter value in getOption and pushes an update', function () {
        livewire(SchemaFilteredTestWidget::class)
            ->set('filters.range', 'long')
            ->assertDispatched('updateOptions')
            ->assertChartOptions(fn (array $options): bool => $options['series'][0]['data'] === [1, 2, 3, 4, 5]);
    });

    it('renders the filter trigger only for a widget implementing the contract', function () {
        // The dropdown UI is gated on the package `HasFiltersSchema` contract,
        // not on the trait — a plain widget shows no filter trigger.
        livewire(SchemaFilteredTestWidget::class)
            ->assertSee('Schema filtered');
    });
});
