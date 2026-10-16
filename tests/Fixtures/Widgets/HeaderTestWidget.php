<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Tests\Fixtures\Widgets;

use BackedEnum;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\BarSeries;
use Happenv\FilamentEnhancedCharts\Widgets\EnhancedChartWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

/**
 * A widget that fills both header slots: an icon beside the heading and content at the end of
 * the header row.
 */
class HeaderTestWidget extends EnhancedChartWidget
{
    protected static ?string $heading = 'With header extras';

    protected static string | BackedEnum | null $headingIcon = 'heroicon-o-chart-pie';

    protected function getAfterHeader(): Htmlable
    {
        return new HtmlString('<span class="after-header-probe">Estimated</span>');
    }

    protected function getOption(): Option
    {
        return Option::make()->series(BarSeries::make()->data([1]));
    }
}
