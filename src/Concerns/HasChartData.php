<?php

namespace Happenv\FilamentEnhancedCharts\Concerns;

use Happenv\FilamentEnhancedCharts\Data\ChartData;
use Happenv\FilamentEnhancedCharts\Enums\ChartType;
use Happenv\FilamentEnhancedCharts\Option\Axis\CategoryAxis;
use Happenv\FilamentEnhancedCharts\Option\Component\Legend;
use Happenv\FilamentEnhancedCharts\Option\Component\Tooltip;
use Happenv\FilamentEnhancedCharts\Option\Option;

/**
 * The high-level shortcut for the common dashboard chart: declare the data and
 * (optionally) the type, and the widget builds a sensible `Option` for you —
 * no option-tree assembly. For anything richer (a second series, custom
 * styling, marks) implement `getOption()` directly instead; this trait is the
 * shallow-end convenience, not a replacement for the typed model.
 *
 * ```php
 * class OrdersChart extends EnhancedChartWidget
 * {
 *     use HasChartData;
 *
 *     protected function getData(): ChartData
 *     {
 *         return ChartData::fromPairs(Order::query()->pluck('total', 'channel'));
 *     }
 *
 *     protected function chartType(): ChartType
 *     {
 *         return ChartType::Bar;
 *     }
 * }
 * ```
 */
trait HasChartData /** @phpstan-ignore trait.unused (consumer-facing API — used by app widgets, not the package itself, like Filament's own HasFiltersSchema) */
{
    abstract protected function getData(): ChartData;

    /** The chart type; override to switch (defaults to a line chart). */
    protected function chartType(): ChartType
    {
        return ChartType::Line;
    }

    protected function getOption(): Option
    {
        $data = $this->getData();
        $type = $this->chartType();
        $series = $type->seriesFrom($data);

        if ($type->isCartesian()) {
            return Option::cartesian()
                ->xAxis(CategoryAxis::make()->data($data->labels()))
                ->series($series);
        }

        return Option::make()
            ->tooltip(Tooltip::make()->trigger('item'))
            ->legend(Legend::make()->top(0))
            ->series($series);
    }
}
