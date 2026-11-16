<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetMeasure;
use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Exceptions\ChartPresetException;
use Happenv\FilamentEnhancedCharts\Option\Axis\CategoryAxis;
use Happenv\FilamentEnhancedCharts\Option\Axis\ValueAxis;
use Happenv\FilamentEnhancedCharts\Option\Component\Grid;
use Happenv\FilamentEnhancedCharts\Option\Component\Legend;
use Happenv\FilamentEnhancedCharts\Option\Component\Tooltip;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\Series;

/**
 * Base for presets: guards `option()` with `accepts()` and carries the layout
 * defaults the built-ins share (scrolling legend, tooltips, a roomy cartesian
 * grid) so every chart looks right at ~400px in a narrow panel.
 */
abstract class AbstractChartPreset implements ChartPreset
{
    protected const string ONE_DIMENSION_ONE_MEASURE = '1 dimension (category or time) and 1 measure';

    protected const string ONE_DIMENSION_MEASURES = '1 dimension (category or time) and 1 or more measures (one series per measure)';

    protected const string BREAKDOWN = '2 dimensions (the first on the x axis, the second splitting it into series) and 1 measure, or 1 dimension and 2 or more measures';

    final public function option(ChartDataset $dataset): Option
    {
        $shape = $dataset->shape();

        if (! $this->accepts($shape)) {
            throw ChartPresetException::notAccepted($this, $shape);
        }

        return $this->build($dataset);
    }

    abstract protected function build(ChartDataset $dataset): Option;

    protected function scrollLegend(bool $atBottom = false): Legend
    {
        $legend = Legend::make()->type('scroll');

        return $atBottom ? $legend->bottom(0) : $legend->top(0);
    }

    protected function itemTooltip(): Tooltip
    {
        return Tooltip::make()->trigger('item')->confine();
    }

    protected function axisTooltip(): Tooltip
    {
        return Tooltip::make()->trigger('axis')->confine();
    }

    /**
     * A cartesian chart: a category axis carrying the labels, a value axis, a
     * tooltip, and a scrolling legend when it carries more than one series.
     *
     * @param  list<Series>  $series
     */
    protected function cartesianOption(CategoryAxis $categories, array $series, bool $horizontal = false): Option
    {
        $option = Option::make()
            ->tooltip($this->axisTooltip())
            ->grid(Grid::make()->containLabel()->left(8)->right(16)->bottom(8)->top(count($series) > 1 ? 44 : 16))
            ->series(...$series);

        if (count($series) > 1) {
            $option->legend($this->scrollLegend());
        }

        return $horizontal
            ? $option->xAxis(ValueAxis::make())->yAxis($categories->inverse())
            : $option->xAxis($categories)->yAxis(ValueAxis::make());
    }

    /**
     * The smallest "round" number (1, 2, 5 × a power of ten) that is not below
     * the value — an axis/gauge maximum that leaves the data some headroom.
     */
    protected function niceCeiling(int | float $value): int | float
    {
        if ($value <= 0) {
            return 1;
        }

        $magnitude = 10 ** floor(log10($value));

        foreach ([1, 2, 5, 10] as $step) {
            if ($step * $magnitude >= $value) {
                return $this->wholeIfPossible($step * $magnitude);
            }
        }

        return $this->wholeIfPossible(10 * $magnitude);
    }

    private function wholeIfPossible(float $value): int | float
    {
        return $value === floor($value) && abs($value) < PHP_INT_MAX ? (int) $value : $value;
    }

    protected function oneDimensionMeasures(DatasetShape $shape, int $minMeasures = 1, ?int $maxMeasures = null): bool
    {
        return $shape->dimensionCount() === 1
            && $shape->measures >= $minMeasures
            && ($maxMeasures === null || $shape->measures <= $maxMeasures);
    }

    /** Two dimensions and one measure, or one dimension and several measures: "x + series" data. */
    protected function acceptsBreakdown(DatasetShape $shape): bool
    {
        return ($shape->dimensionCount() === 2 && $shape->measures === 1)
            || $this->oneDimensionMeasures($shape, 2);
    }

    /**
     * The category labels and the series of a breakdown: the first dimension is
     * the x axis; the second dimension (with one measure) or the measures (with
     * one dimension) become the series.
     *
     * @return array{categories: list<string>, series: list<array{name: string, values: list<int|float|null>}>}
     */
    protected function breakdown(ChartDataset $dataset): array
    {
        $x = $dataset->dimensions[0]->name;

        if (count($dataset->dimensions) === 2) {
            return [
                'categories' => $dataset->distinct($x),
                'series' => $dataset->breakdown($x, $dataset->dimensions[1]->name, $dataset->measures[0]->name),
            ];
        }

        return [
            'categories' => $dataset->labels($x),
            'series' => array_map(
                static fn (DatasetMeasure $measure): array => ['name' => $measure->label, 'values' => $dataset->values($measure->name)],
                $dataset->measures,
            ),
        ];
    }
}
