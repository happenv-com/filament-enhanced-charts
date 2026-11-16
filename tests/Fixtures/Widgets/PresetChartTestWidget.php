<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Tests\Fixtures\Widgets;

use Happenv\FilamentEnhancedCharts\Concerns\HasChartPreset;
use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Tests\Fixtures\ChartDatasets;
use Happenv\FilamentEnhancedCharts\Widgets\EnhancedChartWidget;

/**
 * A widget built the dataset way: it declares only a dataset and a preset name;
 * the `HasChartPreset` trait supplies `getOption()`.
 */
class PresetChartTestWidget extends EnhancedChartWidget
{
    use HasChartPreset;

    protected static ?string $heading = 'Preset chart';

    public string $preset = 'stacked-bar';

    public bool $totalOnly = false;

    protected function getDataset(): ChartDataset
    {
        return $this->totalOnly ? ChartDatasets::total() : ChartDatasets::perMonthPerChannel();
    }

    protected function chartPreset(): string
    {
        return $this->preset;
    }
}
