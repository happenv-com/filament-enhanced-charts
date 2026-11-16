<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Enums\DimensionKind;
use Happenv\FilamentEnhancedCharts\Option\Component\SingleAxis;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\ThemeRiverSeries;
use Happenv\FilamentEnhancedCharts\Presets\AbstractChartPreset;

final class ThemeRiverPreset extends AbstractChartPreset
{
    public function name(): string
    {
        return 'theme-river';
    }

    public function description(): string
    {
        return 'Shows how the share of several categories rises and falls over time as a flowing stream.';
    }

    public function requirement(): string
    {
        return '2 dimensions (1 time and 1 category or region, in either order) and 1 measure';
    }

    public function accepts(DatasetShape $shape): bool
    {
        return $shape->dimensionCount() === 2
            && $shape->measures === 1
            && $shape->hasKind(DimensionKind::Time)
            && ($shape->hasKind(DimensionKind::Category) || $shape->hasKind(DimensionKind::Region));
    }

    protected function build(ChartDataset $dataset): Option
    {
        $time = $dataset->dimensions[0]->kind === DimensionKind::Time ? $dataset->dimensions[0] : $dataset->dimensions[1];
        $category = $dataset->dimensions[0]->kind === DimensionKind::Time ? $dataset->dimensions[1] : $dataset->dimensions[0];

        $periods = $dataset->distinct($time->name);
        $names = $dataset->distinct($category->name);

        $cells = [];
        foreach ($dataset->breakdown($time->name, $category->name, $dataset->measures[0]->name) as $series) {
            $cells[$series['name']] = $series['values'];
        }

        // A stream needs every category at every period: a missing combination is zero, not a gap.
        $data = [];
        foreach ($periods as $index => $period) {
            foreach ($names as $name) {
                $data[] = [$period, $cells[$name][$index] ?? 0, $name];
            }
        }

        return Option::make()
            ->tooltip($this->axisTooltip())
            ->legend($this->scrollLegend())
            ->singleAxis(SingleAxis::make()->type('category')->data($periods)->top(48)->bottom(32)->left(16)->right(24))
            ->series(ThemeRiverSeries::make()->data($data)->boundaryGap(['6%', '6%']));
    }
}
