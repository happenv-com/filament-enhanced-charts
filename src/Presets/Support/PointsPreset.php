<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Support;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Option\Axis\ValueAxis;
use Happenv\FilamentEnhancedCharts\Option\Component\Grid;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\Series;
use Happenv\FilamentEnhancedCharts\Presets\AbstractChartPreset;
use Happenv\FilamentEnhancedCharts\Support\NumberFormat;

/**
 * Charts that plot one measure against another with a labelled point per
 * dimension value (scatter, effect-scatter): 1 dimension and exactly 2 measures.
 * Each row is its own series, so the legend names the points.
 */
abstract class PointsPreset extends AbstractChartPreset
{
    /** @param  array{0: int|float, 1: int|float}  $point */
    abstract protected function point(string $name, array $point): Series;

    final public function requirement(): string
    {
        return '1 dimension (category, time or region; every row is a point named by it) and exactly 2 measures (x and y)';
    }

    final protected function acceptsStructure(DatasetShape $shape): bool
    {
        return $this->oneDimensionMeasures($shape, 2, 2);
    }

    final protected function build(ChartDataset $dataset): Option
    {
        [$xMeasure, $yMeasure] = $dataset->measures;

        $names = $dataset->labels($dataset->dimensions[0]->name);
        $xs = $dataset->values($xMeasure->name);
        $ys = $dataset->values($yMeasure->name);

        $xFormat = $xMeasure->resolvedFormat();
        $yFormat = $yMeasure->resolvedFormat();

        $series = [];
        foreach ($names as $row => $name) {
            if ($xs[$row] === null || $ys[$row] === null) {
                continue;
            }

            $point = $this->point($name, [$xs[$row], $ys[$row]]);
            $series[] = $xFormat instanceof NumberFormat || $yFormat instanceof NumberFormat ? $point->formatDimensions($xFormat, $yFormat) : $point;
        }

        $xAxis = ValueAxis::make()->name($xMeasure->label)->nameLocation('middle')->nameGap(28)->scale();
        $yAxis = ValueAxis::make()->name($yMeasure->label)->nameLocation('end')->scale();
        if ($xFormat instanceof NumberFormat) {
            $xAxis->format($xFormat);
        }
        if ($yFormat instanceof NumberFormat) {
            $yAxis->format($yFormat);
        }

        return Option::make()
            ->tooltip($this->itemTooltip())
            ->legend($this->scrollLegend())
            ->grid(Grid::make()->containLabel()->left(8)->right(24)->top(44)->bottom(36))
            ->xAxis($xAxis)
            ->yAxis($yAxis)
            ->series(...$series);
    }
}
