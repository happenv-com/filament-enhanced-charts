<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Enums;

use Happenv\FilamentEnhancedCharts\Data\ChartData;
use Happenv\FilamentEnhancedCharts\Option\Series\BarSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\LineSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\PieSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\Series;

/**
 * The chart type for the high-level `HasChartData` widget convenience — a
 * curated subset of series that map cleanly onto the `labels + values` /
 * `toDataPoints()` shape a `ChartData` produces. Anything richer (a second
 * series, x/y scatter, marks, custom styling) drops to `getOption()` with the
 * full typed series builders.
 */
enum ChartType: string
{
    case Line = 'line';
    case Area = 'area';
    case Bar = 'bar';
    case Pie = 'pie';

    /**
     * The series builder for this type, loaded from the given data in the
     * shape the type expects (values for a cartesian series, named
     * `DataPoint`s for a pie).
     */
    public function seriesFrom(ChartData $data): Series
    {
        return match ($this) {
            self::Line => LineSeries::make()->data($data->values()),
            self::Area => LineSeries::make()->areaStyle()->data($data->values()),
            self::Bar => BarSeries::make()->data($data->values()),
            self::Pie => PieSeries::make()->data($data->toDataPoints()),
        };
    }

    /** Whether this type sits on a cartesian grid (needs a category x-axis + a value y-axis). */
    public function isCartesian(): bool
    {
        return $this !== self::Pie;
    }
}
