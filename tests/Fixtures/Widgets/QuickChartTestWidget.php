<?php

namespace Happenv\FilamentEnhancedCharts\Tests\Fixtures\Widgets;

use Happenv\FilamentEnhancedCharts\Concerns\HasChartData;
use Happenv\FilamentEnhancedCharts\Data\ChartData;
use Happenv\FilamentEnhancedCharts\Enums\ChartType;
use Happenv\FilamentEnhancedCharts\Widgets\EnhancedChartWidget;

/**
 * A widget built the high-level way: it declares only its data and type; the
 * `HasChartData` trait supplies `getOption()`.
 */
class QuickChartTestWidget extends EnhancedChartWidget
{
    use HasChartData;

    protected static ?string $heading = 'Quick chart';

    public ChartType $type = ChartType::Bar;

    protected function getData(): ChartData
    {
        return ChartData::fromPairs(['B2B' => 60, 'B2C' => 40]);
    }

    protected function chartType(): ChartType
    {
        return $this->type;
    }
}
