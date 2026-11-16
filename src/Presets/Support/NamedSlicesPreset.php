<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Support;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Option\DataPoint;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\Series;
use Happenv\FilamentEnhancedCharts\Presets\AbstractChartPreset;

/**
 * Charts that draw one named slice per dimension value (pie, donut, rose,
 * funnel): 1 dimension and 1 measure, a legend that scrolls, an item tooltip.
 */
abstract class NamedSlicesPreset extends AbstractChartPreset
{
    /** @param  list<DataPoint>  $slices */
    abstract protected function series(array $slices): Series;

    protected function legendAtBottom(): bool
    {
        return true;
    }

    final public function requirement(): string
    {
        return self::ONE_DIMENSION_ONE_MEASURE . self::SINGLE_VALUED;
    }

    /** Slices are the parts of one whole, so the dimension that names them must add up to it. */
    final public function partitioningDimensions(DatasetShape $shape): array
    {
        return [0];
    }

    final protected function acceptsStructure(DatasetShape $shape): bool
    {
        return $this->oneDimensionMeasures($shape, 1, 1);
    }

    final protected function build(ChartDataset $dataset): Option
    {
        $slices = array_map(
            static fn (array $total): DataPoint => DataPoint::make($total['value'] ?? 0)->name($total['name']),
            $dataset->totals($dataset->dimensions[0]->name, $dataset->measures[0]->name),
        );

        return Option::make()
            ->tooltip($this->itemTooltip())
            ->legend($this->scrollLegend($this->legendAtBottom()))
            ->series($this->series($slices));
    }
}
