<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Option\Axis\CategoryAxis;
use Happenv\FilamentEnhancedCharts\Option\Component\Grid;
use Happenv\FilamentEnhancedCharts\Option\Component\VisualMap;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\HeatmapSeries;
use Happenv\FilamentEnhancedCharts\Presets\AbstractChartPreset;

final class HeatmapPreset extends AbstractChartPreset
{
    public function name(): string
    {
        return 'heatmap';
    }

    public function description(): string
    {
        return 'Shows how a measure varies across every combination of two dimensions as a grid of cells coloured by value.';
    }

    public function requirement(): string
    {
        return '2 dimensions (category, time or region; the first on the x axis, the second on the y axis) and 1 measure';
    }

    public function accepts(DatasetShape $shape): bool
    {
        return $shape->dimensionCount() === 2 && $shape->measures === 1;
    }

    protected function build(ChartDataset $dataset): Option
    {
        $xName = $dataset->dimensions[0]->name;
        $yName = $dataset->dimensions[1]->name;

        $xs = $dataset->distinct($xName);
        $ys = $dataset->distinct($yName);
        $xIndex = array_flip($xs);
        $yIndex = array_flip($ys);

        $xLabels = $dataset->labels($xName);
        $yLabels = $dataset->labels($yName);
        $values = $dataset->values($dataset->measures[0]->name);

        $cells = [];
        foreach ($values as $row => $value) {
            if ($value === null) {
                continue;
            }

            $key = $xIndex[$xLabels[$row]] . ':' . $yIndex[$yLabels[$row]];
            $cells[$key] = [
                $xIndex[$xLabels[$row]],
                $yIndex[$yLabels[$row]],
                ($cells[$key][2] ?? 0) + $value,
            ];
        }

        $cells = array_values($cells);
        $measured = array_column($cells, 2);
        $min = $measured === [] ? 0 : min($measured);
        $max = $measured === [] ? 1 : max($measured);

        return Option::make()
            ->tooltip($this->itemTooltip())
            ->grid(Grid::make()->containLabel()->left(8)->right(16)->top(16)->bottom(64))
            ->xAxis(CategoryAxis::make()->data($xs)->splitArea())
            ->yAxis(CategoryAxis::make()->data($ys)->inverse()->splitArea())
            ->visualMap(
                VisualMap::continuous()
                    ->min($min)
                    ->max($max > $min ? $max : $min + 1)
                    ->orient('horizontal')
                    ->left('center')
                    ->bottom(8)
                    ->colors('#e0f2fe', '#0369a1'),
            )
            ->series(HeatmapSeries::make()->name($dataset->measures[0]->label)->data($cells));
    }
}
