<?php

namespace Happenv\FilamentEnhancedCharts\Concerns;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Presets\ChartPresets;
use Happenv\FilamentEnhancedCharts\Support\ChartMaps;

/**
 * Draw an `EnhancedChartWidget` from a tabular dataset and a preset name — no
 * option-tree assembly and no chart types. The registry builds the `Option`;
 * an unknown preset, or one that does not accept the dataset's shape, throws
 * instead of rendering a blank chart.
 *
 * ```php
 * class OrdersPerChannelChart extends EnhancedChartWidget
 * {
 *     use HasChartPreset;
 *
 *     protected function getDataset(): ChartDataset
 *     {
 *         return ChartDataset::fromRows(…);
 *     }
 *
 *     protected function chartPreset(): string
 *     {
 *         return 'stacked-bar';
 *     }
 * }
 * ```
 */
trait HasChartPreset /** @phpstan-ignore trait.unused (consumer-facing API — used by app widgets, not the package itself, like HasChartData) */
{
    abstract protected function getDataset(): ChartDataset;

    /** The `ChartPreset::name()` to draw the dataset with, e.g. `stacked-bar`. */
    abstract protected function chartPreset(): string;

    protected function getOption(): Option
    {
        return app(ChartPresets::class)->option($this->chartPreset(), $this->getDataset());
    }

    /**
     * The world map of the `world-map` / `flow-lines` presets, and only when the
     * drawn chart uses it; a widget that needs other maps overrides this.
     *
     * @return array<string, string>
     */
    public function getMaps(): array
    {
        return $this->readyToLoad ? ChartMaps::for($this->resolveOptions()) : [];
    }
}
