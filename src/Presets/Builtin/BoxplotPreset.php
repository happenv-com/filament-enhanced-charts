<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Enums\MeasureRole;
use Happenv\FilamentEnhancedCharts\Option\Axis\CategoryAxis;
use Happenv\FilamentEnhancedCharts\Option\Axis\ValueAxis;
use Happenv\FilamentEnhancedCharts\Option\Component\Grid;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\BoxplotSeries;
use Happenv\FilamentEnhancedCharts\Presets\AbstractChartPreset;

final class BoxplotPreset extends AbstractChartPreset
{
    /** The order ECharts reads a box in. */
    private const array ROLES = [MeasureRole::Min, MeasureRole::Q1, MeasureRole::Median, MeasureRole::Q3, MeasureRole::Max];

    public function name(): string
    {
        return 'boxplot';
    }

    public function description(): string
    {
        return 'Shows how the values of a measure are spread within each dimension value as a box from the lower to the upper quartile, with the median marked and whiskers out to the minimum and maximum.';
    }

    public function requirement(): string
    {
        return '1 dimension (category, time or region; every value is one box) and exactly 5 measures carrying the roles min, q1, median, q3 and max, in any order';
    }

    protected function acceptsStructure(DatasetShape $shape): bool
    {
        return $this->oneDimensionMeasures($shape, 5, 5) && $shape->hasExactlyRoles(...self::ROLES);
    }

    protected function build(ChartDataset $dataset): Option
    {
        $columns = array_map(
            $dataset->valuesOfRole(...),
            self::ROLES,
        );

        // A box needs all five statistics: a row missing one cannot be drawn and is left out.
        $categories = [];
        $boxes = [];
        foreach ($dataset->labels($dataset->dimensions[0]->name) as $row => $label) {
            $box = array_column($columns, $row);

            if (! in_array(null, $box, true)) {
                $categories[] = $label;
                $boxes[] = $box;
            }
        }

        return Option::make()
            ->tooltip($this->itemTooltip())
            ->grid(Grid::make()->containLabel()->left(8)->right(16)->top(16)->bottom(8))
            ->xAxis(CategoryAxis::make()->data($categories))
            ->yAxis(ValueAxis::make()->scale())
            ->series(BoxplotSeries::make()->data($boxes));
    }
}
