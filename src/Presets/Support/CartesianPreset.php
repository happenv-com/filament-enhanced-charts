<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Support;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Option\Axis\CategoryAxis;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\Series;
use Happenv\FilamentEnhancedCharts\Presets\AbstractChartPreset;

/**
 * Charts on a category axis with one series per measure (or per value of a
 * second dimension): bars, lines, areas. The first dimension is the axis; its
 * labels are used as given, so a time dimension never has to be an ISO date.
 */
abstract class CartesianPreset extends AbstractChartPreset
{
    /** @param  list<int|float|null>  $values */
    abstract protected function series(string $name, array $values): Series;

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

        return $this->cartesianOption(
            $this->categoryAxis()->data($breakdown['categories']),
            array_map(fn (array $series): Series => $this->series($series['name'], $series['values']), $breakdown['series']),
            $this->horizontal(),
        );
    }
}
