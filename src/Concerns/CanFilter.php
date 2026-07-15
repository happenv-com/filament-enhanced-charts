<?php

namespace Happenv\FilamentEnhancedCharts\Concerns;

use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Support\Enums\Width;
use Happenv\FilamentEnhancedCharts\Widgets\EnhancedChartWidget;

/**
 * @mixin EnhancedChartWidget
 */
trait CanFilter
{
    use InteractsWithSchemas;

    protected static Width | string $filterFormWidth = Width::ExtraSmall;

    public ?string $filter = null;

    /**
     * Mirrors the Alpine-side dropdown state (synced via $watch) so polling
     * skips chart updates while the filter dropdown is open. MUST default to
     * `false` like its Alpine counterpart — a `true` default would permanently
     * gate updateOptions() on widgets whose dropdown is never touched.
     */
    public bool $dropdownOpen = false;

    /**
     * Retrieves the simple filter options.
     *
     * @return array|null The simple filter options.
     */
    protected function getFilters(): ?array
    {
        return null;
    }

    /**
     * Update the filter and emit an event with the updated options.
     */
    public function updatedFilter(): void
    {
        // Prime the per-request memo so the subsequent re-render doesn't
        // compute (and query) the options a second time.
        $options = $this->resolvedOptions = $this->getOptions();
        $this->optionsHash = $this->hashOptions($options);

        $this->dispatch('updateOptions', options: $options)
            ->self();
    }

    /**
     * Retrieves the value of the static property $filterFormWidth.
     *
     * @return Width | string The value of the $filterFormWidth property, which is either a MaxWidth instance or a string.
     */
    protected function getFilterFormWidth(): Width | string
    {
        return static::$filterFormWidth;
    }
}
