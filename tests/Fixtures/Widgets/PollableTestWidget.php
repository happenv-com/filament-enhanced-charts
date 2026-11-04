<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Tests\Fixtures\Widgets;

use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\BarSeries;
use Happenv\FilamentEnhancedCharts\Widgets\EnhancedChartWidget;

/**
 * A trivial widget whose single data point is a public Livewire property plus a
 * value from outside the component (`$outside`, standing in for the database a
 * poll re-reads), so a test can change the options with or without a Livewire
 * update.
 */
class PollableTestWidget extends EnhancedChartWidget
{
    protected static ?string $heading = 'Pollable';

    public int $value = 1;

    public static int $outside = 0;

    protected ?string $pollingInterval = '5s';

    protected function getOption(): Option
    {
        return Option::make()->series(BarSeries::make()->data([$this->value + static::$outside]));
    }
}
