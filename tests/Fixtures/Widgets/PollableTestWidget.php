<?php

namespace Happenv\FilamentEnhancedCharts\Tests\Fixtures\Widgets;

use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\BarSeries;
use Happenv\FilamentEnhancedCharts\Widgets\EnhancedChartWidget;

/**
 * A trivial widget whose single data point is a public Livewire property, so a
 * test can mutate it between `updateOptions()` calls to exercise the poll
 * dirty-check.
 */
class PollableTestWidget extends EnhancedChartWidget
{
    protected static ?string $heading = 'Pollable';

    public int $value = 1;

    protected ?string $pollingInterval = '5s';

    protected function getOption(): Option
    {
        return Option::make()->series(BarSeries::make()->data([$this->value]));
    }
}
