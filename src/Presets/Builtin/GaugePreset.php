<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Exceptions\ChartPresetException;
use Happenv\FilamentEnhancedCharts\Option\DataPoint;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\GaugeSeries;
use Happenv\FilamentEnhancedCharts\Presets\AbstractChartPreset;

final class GaugePreset extends AbstractChartPreset
{
    public function name(): string
    {
        return 'gauge';
    }

    public function description(): string
    {
        return 'Shows a single number as a dial, with a scale that leaves headroom beyond the value (above it, or below it for a negative one).';
    }

    public function requirement(): string
    {
        return 'no dimensions and 1 measure (a single value)';
    }

    public function accepts(DatasetShape $shape): bool
    {
        return $shape->dimensionCount() === 0 && $shape->measures === 1;
    }

    protected function build(ChartDataset $dataset): Option
    {
        if (count($dataset->rows) > 1) {
            throw ChartPresetException::unusableData($this, 'a gauge shows one value, but the dataset has ' . count($dataset->rows) . ' rows');
        }

        $measure = $dataset->measures[0];
        $value = $dataset->values($measure->name)[0] ?? 0;

        // The dial always contains the value: a negative one runs from a nice
        // negative minimum up to 0, a non-negative one from 0 up to a nice maximum.
        [$min, $max] = $value < 0
            ? [-$this->niceCeiling(-$value), 0]
            : [0, $this->niceCeiling($value)];

        return Option::make()
            ->tooltip($this->itemTooltip())
            ->series(
                GaugeSeries::make()
                    ->radius('88%')
                    ->center(['50%', '58%'])
                    ->min($min)
                    ->max($max)
                    ->progress(['show' => true, 'width' => 14])
                    ->axisLineWidth(14)
                    ->detail(['fontSize' => 28, 'offsetCenter' => [0, '62%'], 'valueAnimation' => true])
                    ->title(['fontSize' => 14, 'offsetCenter' => [0, '84%']])
                    ->data([DataPoint::make($value)->name($measure->label)]),
            );
    }
}
