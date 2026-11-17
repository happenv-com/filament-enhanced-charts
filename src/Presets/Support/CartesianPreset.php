<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Support;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Option\Axis\CategoryAxis;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\Series;
use Happenv\FilamentEnhancedCharts\Presets\AbstractChartPreset;
use Happenv\FilamentEnhancedCharts\Support\NumberFormat;

/**
 * Charts on a category axis with one series per measure (or per value of a
 * second dimension): bars, lines, areas. The first dimension is the axis; its
 * labels are used as given, so a time dimension never has to be an ISO date.
 */
abstract class CartesianPreset extends AbstractChartPreset
{
    /**
     * @param  list<int|float|null>  $values
     * @param  int  $axis  the index of the value axis (one per unit) the series is drawn on — a stacked series
     *                     stacks only with the others on the same axis, so measures of different units never add up
     */
    abstract protected function series(string $name, array $values, int $axis = 0): Series;

    protected function horizontal(): bool
    {
        return false;
    }

    protected function categoryAxis(): CategoryAxis
    {
        return CategoryAxis::make();
    }

    final protected function build(ChartDataset $dataset): Option
    {
        $breakdown = $this->breakdown($dataset);
        $units = $this->unitGroups($breakdown['series']);
        $multiAxis = count($units) > 1;

        $series = [];
        foreach ($breakdown['series'] as $index => $item) {
            $axis = $multiAxis ? (int) array_search($item['unit'], $units, true) : 0;
            $drawn = $this->series($item['name'], $item['values'], $axis);

            if ($multiAxis) {
                $drawn = ($this->horizontal() ? $drawn->xAxisIndex($axis) : $drawn->yAxisIndex($axis))->color($this->seriesColor($index));
            }

            if ($item['format'] instanceof NumberFormat) {
                $drawn = $drawn->format($item['format']);
            }

            $series[] = $drawn;
        }

        return $this->cartesianOption(
            $this->categoryAxis()->data($breakdown['categories']),
            $series,
            $this->horizontal(),
            $this->unitAxes($units, array_column($breakdown['series'], 'unit'), $this->horizontal(), array_column($breakdown['series'], 'format')),
            $this->firstFormat($breakdown['series']),
        );
    }
}
