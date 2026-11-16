<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Option\Axis\ValueAxis;
use Happenv\FilamentEnhancedCharts\Option\Component\Grid;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\ScatterSeries;
use Happenv\FilamentEnhancedCharts\Presets\AbstractChartPreset;

final class ScatterPreset extends AbstractChartPreset
{
    public function name(): string
    {
        return 'scatter';
    }

    public function description(): string
    {
        return 'Plots one measure against another so a relationship or outlier shows up, with every dimension value as a labelled point.';
    }

    public function requirement(): string
    {
        return '1 dimension (category or time; every row is a point named by it) and exactly 2 measures (x and y)';
    }

    public function accepts(DatasetShape $shape): bool
    {
        return $this->oneDimensionMeasures($shape, 2, 2);
    }

    protected function build(ChartDataset $dataset): Option
    {
        [$xMeasure, $yMeasure] = $dataset->measures;

        $names = $dataset->labels($dataset->dimensions[0]->name);
        $xs = $dataset->values($xMeasure->name);
        $ys = $dataset->values($yMeasure->name);

        $series = [];
        foreach ($names as $row => $name) {
            if ($xs[$row] === null || $ys[$row] === null) {
                continue;
            }

            $series[] = ScatterSeries::make()->name($name)->symbolSize(12)->data([[$xs[$row], $ys[$row]]]);
        }

        return Option::make()
            ->tooltip($this->itemTooltip())
            ->legend($this->scrollLegend())
            ->grid(Grid::make()->containLabel()->left(8)->right(24)->top(44)->bottom(36))
            ->xAxis(ValueAxis::make()->name($xMeasure->label)->nameLocation('middle')->nameGap(28)->scale())
            ->yAxis(ValueAxis::make()->name($yMeasure->label)->nameLocation('end')->scale())
            ->series(...$series);
    }
}
