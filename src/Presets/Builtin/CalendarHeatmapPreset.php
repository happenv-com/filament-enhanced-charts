<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Enums\DimensionKind;
use Happenv\FilamentEnhancedCharts\Exceptions\ChartPresetException;
use Happenv\FilamentEnhancedCharts\Option\Component\Calendar;
use Happenv\FilamentEnhancedCharts\Option\Component\VisualMap;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\HeatmapSeries;
use Happenv\FilamentEnhancedCharts\Presets\AbstractChartPreset;

final class CalendarHeatmapPreset extends AbstractChartPreset
{
    public function name(): string
    {
        return 'calendar-heatmap';
    }

    public function description(): string
    {
        return 'Shows how a measure varies from day to day as a calendar of cells coloured by value, revealing weekly and seasonal patterns.';
    }

    public function requirement(): string
    {
        return '1 time dimension with one value per day, written as YYYY-MM-DD, and 1 measure';
    }

    protected function acceptsStructure(DatasetShape $shape): bool
    {
        return $shape->dimensionCount() === 1
            && $shape->kindAt(0) === DimensionKind::Time
            && $shape->measures === 1;
    }

    protected function build(ChartDataset $dataset): Option
    {
        $days = [];
        foreach ($dataset->totals($dataset->dimensions[0]->name, $dataset->measures[0]->name) as $total) {
            $day = $this->day($total['name']);

            if ($total['value'] !== null) {
                $days[$day] = ($days[$day] ?? 0) + $total['value'];
            }
        }
        ksort($days);

        $dates = array_map(strval(...), array_keys($days));
        $min = $days === [] ? 0 : min($days);
        $max = $days === [] ? 1 : max($days);
        $range = $dates === [] ? date('Y') : [$dates[0], $dates[count($dates) - 1]];

        return Option::make()
            ->tooltip($this->itemTooltip())
            ->visualMap(
                VisualMap::continuous()
                    ->min(min(0, $min))
                    ->max($max > 0 ? $max : 1)
                    ->orient('horizontal')
                    ->left('center')
                    ->top(0)
                    ->colors('#e0f2fe', '#0369a1'),
            )
            ->calendar(
                Calendar::make()
                    ->range($range)
                    ->cellSize(['auto', 16])
                    ->left(40)
                    ->right(16)
                    ->top(72)
                    ->dayLabel(['firstDay' => 1]),
            )
            ->series(
                HeatmapSeries::make()
                    ->name($dataset->measures[0]->label)
                    ->coordinateSystem('calendar')
                    ->calendarIndex(0)
                    ->data(array_map(static fn (string $date): array => [$date, $days[$date]], $dates)),
            );
    }

    private function day(string $label): string
    {
        $day = substr($label, 0, 10);
        $parsed = date_create_immutable_from_format('!Y-m-d', $day);

        if (preg_match('/^\d{4}-\d{2}-\d{2}(?:$|[ T])/', $label) !== 1 || $parsed === false || $parsed->format('Y-m-d') !== $day) {
            throw ChartPresetException::unusableData($this, "the time value [{$label}] is not a day written as YYYY-MM-DD");
        }

        return $day;
    }
}
