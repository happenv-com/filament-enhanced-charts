<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Exceptions\ChartPresetException;
use Happenv\FilamentEnhancedCharts\Option\Option;

/**
 * A way to draw a `ChartDataset`: it declares which dataset shapes it accepts
 * and builds the matching `Option`. A consumer never names a chart type — it
 * asks the `ChartPresets` registry which presets accept its data and picks by
 * `name()`/`description()`.
 *
 * Implement this (or extend `AbstractChartPreset`) and `ChartPresets::register()`
 * it from a service provider to make a new chart available to every consumer.
 */
interface ChartPreset
{
    /** A stable kebab-case key, e.g. `stacked-bar`. */
    public function name(): string;

    /** One English sentence on what this chart shows best — read by a person or an LLM choosing a chart. */
    public function description(): string;

    /** The accepted data in English, e.g. "1 dimension and 1 measure". */
    public function requirement(): string;

    public function accepts(DatasetShape $shape): bool;

    /**
     * @throws ChartPresetException when the dataset's shape is not accepted
     */
    public function option(ChartDataset $dataset): Option;
}
