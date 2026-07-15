<?php

namespace Happenv\FilamentEnhancedCharts\Tests\Fixtures\Widgets;

use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\BarSeries;
use Happenv\FilamentEnhancedCharts\Widgets\EnhancedChartWidget;

/**
 * A deferred widget: it renders a loading placeholder until `loadWidget()`
 * flips `readyToLoad`, at which point `getOption()` returns real series.
 */
class DeferredTestWidget extends EnhancedChartWidget
{
    protected static ?string $heading = 'Deferred';

    protected static bool $deferLoading = true;

    protected function getOption(): Option
    {
        if (! $this->readyToLoad) {
            return Option::make();
        }

        return Option::make()->series(BarSeries::make()->data([42]));
    }
}
