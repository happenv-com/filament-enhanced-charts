<?php

namespace Happenv\FilamentEnhancedCharts\Tests\Fixtures\Widgets;

use Happenv\FilamentEnhancedCharts\Option\Axis\CategoryAxis;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\LineSeries;
use Happenv\FilamentEnhancedCharts\Widgets\EnhancedChartWidget;

/**
 * A widget with a single-select header filter (`getFilters()` + `$filter`); the
 * active period drives how many data points the chart shows.
 */
class FilteredTestWidget extends EnhancedChartWidget
{
    protected static ?string $heading = 'Filtered';

    public ?string $filter = 'week';

    /** @return array<string, string> */
    protected function getFilters(): ?array
    {
        return [
            'week' => 'Last week',
            'month' => 'Last month',
        ];
    }

    protected function getOption(): Option
    {
        $points = $this->filter === 'month'
            ? range(1, 30)
            : range(1, 7);

        return Option::cartesian()
            ->xAxis(CategoryAxis::make()->data(array_map(strval(...), $points)))
            ->series(LineSeries::make()->data($points));
    }
}
