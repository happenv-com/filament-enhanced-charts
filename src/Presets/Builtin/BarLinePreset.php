<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Option\Axis\CategoryAxis;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\BarSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\LineSeries;
use Happenv\FilamentEnhancedCharts\Presets\AbstractChartPreset;
use Happenv\FilamentEnhancedCharts\Support\NumberFormat;

final class BarLinePreset extends AbstractChartPreset
{
    public function name(): string
    {
        return 'bar-line';
    }

    public function description(): string
    {
        return 'Combines bars and lines on one category axis, so a volume and a rate or trend (orders as bars, conversion as a line) can be read together.';
    }

    public function requirement(): string
    {
        return '1 dimension (category, time or region) and 2 or more measures: the first measure is drawn as bars and the rest as lines; when the measures carry different units, the measures of the first unit are bars, those of every other unit lines, and each unit gets its own value axis';
    }

    protected function acceptsStructure(DatasetShape $shape): bool
    {
        return $this->oneDimensionMeasures($shape, 2);
    }

    protected function build(ChartDataset $dataset): Option
    {
        $breakdown = $this->breakdown($dataset);
        $units = $this->unitGroups($breakdown['series']);
        $multiAxis = count($units) > 1;

        $series = [];
        foreach ($breakdown['series'] as $index => $item) {
            $axis = $multiAxis ? (int) array_search($item['unit'], $units, true) : 0;
            $bars = $multiAxis ? $axis === 0 : $index === 0;

            $drawn = $bars
                ? BarSeries::make()->name($item['name'])->data($item['values'])
                : LineSeries::make()->name($item['name'])->data($item['values']);

            if ($multiAxis) {
                $drawn = $drawn->yAxisIndex($axis)->color($this->seriesColor($index));
            }

            if ($item['format'] instanceof NumberFormat) {
                $drawn = $drawn->format($item['format']);
            }

            $series[] = $drawn;
        }

        return $this->cartesianOption(
            CategoryAxis::make()->data($breakdown['categories']),
            $series,
            false,
            $this->unitAxes($units, array_column($breakdown['series'], 'unit'), false, array_column($breakdown['series'], 'format')),
            $this->firstFormat($breakdown['series']),
        );
    }
}
