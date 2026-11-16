<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetMeasure;
use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Option\Component\Radar;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\RadarSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\AreaStyle;
use Happenv\FilamentEnhancedCharts\Presets\AbstractChartPreset;

final class RadarPreset extends AbstractChartPreset
{
    public function name(): string
    {
        return 'radar';
    }

    public function description(): string
    {
        return 'Compares several entities across the same set of axes, so their overall profiles can be read at a glance.';
    }

    public function requirement(): string
    {
        return '2 dimensions (the first gives the axes, the second the compared entities) and 1 measure, or 1 dimension (the compared entities) and 3 or more measures (the axes)';
    }

    protected function acceptsStructure(DatasetShape $shape): bool
    {
        return ($shape->dimensionCount() === 2 && $shape->measures === 1)
            || $this->oneDimensionMeasures($shape, 3);
    }

    protected function build(ChartDataset $dataset): Option
    {
        if (count($dataset->dimensions) === 2) {
            $axes = $dataset->distinct($dataset->dimensions[0]->name);
            $entities = array_map(
                static fn (array $series): array => [
                    'name' => $series['name'],
                    'value' => array_map(static fn (int | float | null $value): int | float => $value ?? 0, $series['values']),
                ],
                $dataset->breakdown($dataset->dimensions[0]->name, $dataset->dimensions[1]->name, $dataset->measures[0]->name),
            );
        } else {
            $axes = array_map(static fn (DatasetMeasure $measure): string => $measure->label, $dataset->measures);
            $labels = $dataset->labels($dataset->dimensions[0]->name);
            $columns = array_map(static fn (DatasetMeasure $measure): array => $dataset->values($measure->name), $dataset->measures);

            $entities = [];
            foreach ($labels as $row => $label) {
                $entities[] = [
                    'name' => $label,
                    'value' => array_map(static fn (array $column): int | float => $column[$row] ?? 0, $columns),
                ];
            }
        }

        $peak = array_reduce(
            $entities,
            static fn (int | float $carry, array $entity): int | float => max($carry, ...$entity['value']),
            0,
        );
        $max = $this->niceCeiling($peak);

        return Option::make()
            ->tooltip($this->itemTooltip())
            ->legend($this->scrollLegend())
            ->radar(
                Radar::make()
                    ->indicator(array_map(static fn (string $axis): array => ['name' => $axis, 'max' => $max], $axes))
                    ->radius('62%')
                    ->center(['50%', '56%']),
            )
            ->series(RadarSeries::make()->data($entities)->areaStyle(AreaStyle::make()->opacity(0.15)));
    }
}
