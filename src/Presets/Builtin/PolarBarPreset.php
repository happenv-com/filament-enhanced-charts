<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Option\Component\AngleAxis;
use Happenv\FilamentEnhancedCharts\Option\Component\Polar;
use Happenv\FilamentEnhancedCharts\Option\Component\RadiusAxis;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\BarSeries;
use Happenv\FilamentEnhancedCharts\Presets\AbstractChartPreset;

final class PolarBarPreset extends AbstractChartPreset
{
    public function name(): string
    {
        return 'polar-bar';
    }

    public function description(): string
    {
        return 'Shows a measure per dimension value as bars arranged around a circle, which suits cyclic categories such as weekdays or months.';
    }

    public function requirement(): string
    {
        return self::ONE_DIMENSION_ONE_MEASURE;
    }

    public function accepts(DatasetShape $shape): bool
    {
        return $this->oneDimensionMeasures($shape, 1, 1);
    }

    protected function build(ChartDataset $dataset): Option
    {
        $totals = $dataset->totals($dataset->dimensions[0]->name, $dataset->measures[0]->name);

        return Option::make()
            ->tooltip($this->itemTooltip())
            ->polar(Polar::make()->radius(['15%', '78%'])->center(['50%', '50%']))
            ->angleAxis(AngleAxis::make()->type('category')->data(array_column($totals, 'name')))
            ->radiusAxis(RadiusAxis::make())
            ->series(
                BarSeries::make()
                    ->name($dataset->measures[0]->label)
                    ->coordinateSystem('polar')
                    ->roundCap()
                    ->data(array_column($totals, 'value')),
            );
    }
}
