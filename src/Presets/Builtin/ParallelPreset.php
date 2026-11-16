<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetMeasure;
use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Enums\AxisType;
use Happenv\FilamentEnhancedCharts\Option\Component\Parallel;
use Happenv\FilamentEnhancedCharts\Option\Component\ParallelAxis;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\ParallelSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\LineStyle;
use Happenv\FilamentEnhancedCharts\Presets\AbstractChartPreset;

final class ParallelPreset extends AbstractChartPreset
{
    public function name(): string
    {
        return 'parallel';
    }

    public function description(): string
    {
        return 'Compares many entities across several measures at once: every dimension value is a line crossing one vertical axis per measure, so similar profiles run together and outliers stand apart.';
    }

    public function requirement(): string
    {
        return '1 dimension (category, time or region; every value is one line) and 3 or more measures (one parallel axis per measure)';
    }

    protected function acceptsStructure(DatasetShape $shape): bool
    {
        return $this->oneDimensionMeasures($shape, 3);
    }

    protected function build(ChartDataset $dataset): Option
    {
        $columns = array_map(static fn (DatasetMeasure $measure): array => $dataset->values($measure->name), $dataset->measures);

        // A line needs a value on every axis: a row missing one cannot be drawn and is left out.
        $lines = [];
        foreach ($dataset->labels($dataset->dimensions[0]->name) as $row => $label) {
            $values = array_column($columns, $row);

            if (! in_array(null, $values, true)) {
                $lines[] = ['name' => $label, 'value' => $values];
            }
        }

        return Option::make()
            ->tooltip($this->itemTooltip())
            ->parallel(Parallel::make()->left(16)->right(48)->top(40)->bottom(24))
            ->parallelAxis(...array_map(
                static fn (DatasetMeasure $measure, int $index): ParallelAxis => ParallelAxis::make()
                    ->dim($index)
                    ->name($measure->label)
                    ->type(AxisType::Value)
                    ->nameLocation('start')
                    ->scale(),
                $dataset->measures,
                array_keys($dataset->measures),
            ))
            ->series(ParallelSeries::make()->data($lines)->lineStyle(LineStyle::make()->width(2)->opacity(0.55)));
    }
}
