<?php

namespace Happenv\FilamentEnhancedCharts\Tests\Fixtures\Widgets;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;
use Happenv\FilamentEnhancedCharts\Contracts\HasFiltersSchema as HasFiltersSchemaContract;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\LineSeries;
use Happenv\FilamentEnhancedCharts\Widgets\EnhancedChartWidget;

/**
 * A widget with a full filter schema (the `HasFiltersSchema` contract + the
 * Filament chart-widget filters trait). The active `range` filter value, read
 * from `$this->filters`, drives the series.
 */
class SchemaFilteredTestWidget extends EnhancedChartWidget implements HasFiltersSchemaContract
{
    use HasFiltersSchema;

    protected static ?string $heading = 'Schema filtered';

    /**
     * Absorbs the writes the Filament trait makes to `cachedData` (the base
     * chart widget's data cache, which `EnhancedChartWidget` doesn't use).
     */
    public mixed $cachedData = null;

    public function filtersSchema(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('range')
                ->options(['short' => 'Short', 'long' => 'Long'])
                ->default('short'),
        ]);
    }

    public function updatedInteractsWithSchemas(string $statePath): void
    {
        parent::updatedInteractsWithSchemas($statePath);

        $this->updateOptions();
    }

    protected function getOption(): Option
    {
        $data = ($this->filters['range'] ?? 'short') === 'long'
            ? [1, 2, 3, 4, 5]
            : [1, 2];

        return Option::make()->series(LineSeries::make()->data($data));
    }
}
