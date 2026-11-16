<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetMeasure;
use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Option\Axis\CategoryAxis;
use Happenv\FilamentEnhancedCharts\Option\Axis\ValueAxis;
use Happenv\FilamentEnhancedCharts\Option\Component\Grid;
use Happenv\FilamentEnhancedCharts\Option\Component\Legend;
use Happenv\FilamentEnhancedCharts\Option\Component\Tooltip;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\Series;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;
use Happenv\FilamentEnhancedCharts\Presets\Support\PartOfWholeGuard;

/**
 * Base for presets: guards `option()` with `accepts()` and carries the layout
 * defaults the built-ins share (scrolling legend, tooltips, a roomy cartesian
 * grid) so every chart looks right at ~400px in a narrow panel.
 */
abstract class AbstractChartPreset implements ChartPreset, EncodesPartOfWhole
{
    protected const string ONE_DIMENSION_ONE_MEASURE = '1 dimension (category, time or region) and 1 measure';

    protected const string ONE_DIMENSION_MEASURES = '1 dimension (category, time or region) and 1 or more measures (one series per measure; measures with different units each get their own value axis)';

    protected const string BREAKDOWN = '2 dimensions (the first on the x axis, the second splitting it into series) and 1 measure, or 1 dimension and 2 or more measures (measures with different units each get their own value axis and never stack together)';

    /** Appended to a `requirement()` whose chart encodes part-of-whole; the dimension(s) it partitions by must not be multi-valued. */
    protected const string SINGLE_VALUED = '; the dimension must be single-valued';

    protected const string SINGLE_VALUED_DIMENSIONS = '; every dimension must be single-valued';

    /** The colours the series of a multi-axis chart are given explicitly, so an axis can match its series. */
    private const array PALETTE = ['#5070dd', '#b6d634', '#505372', '#ff994d', '#0ca8df', '#ffd10a', '#fb628b', '#785db0', '#3fbfb2'];

    /** Pixels between two value axes on the same side of a multi-axis chart. */
    private const int AXIS_STEP = 60;

    /**
     * Whether the shape is drawable: `acceptsStructure()` (the dimension kinds,
     * measure count and roles) and, for a preset that encodes part-of-whole, no
     * multi-valued dimension among `partitioningDimensions()`. A preset that
     * overrides this itself still gets the second check in `option()` and in
     * `ChartPresets::accepting()`.
     */
    public function accepts(DatasetShape $shape): bool
    {
        return $this->acceptsStructure($shape) && ! PartOfWholeGuard::refuses($this, $shape);
    }

    /**
     * The shape check proper, apart from the multi-valued rule. Built-ins implement
     * this; a preset that overrides `accepts()` instead may leave the default,
     * which accepts nothing.
     */
    protected function acceptsStructure(DatasetShape $shape): bool
    {
        return false;
    }

    /** No part-of-whole by default; a preset that encodes one names the dimensions it partitions by. */
    public function partitioningDimensions(DatasetShape $shape): array
    {
        return [];
    }

    final public function option(ChartDataset $dataset): Option
    {
        $shape = $dataset->shape();

        if (! $this->accepts($shape) || PartOfWholeGuard::refuses($this, $shape)) {
            throw PartOfWholeGuard::exceptionFor($this, $dataset);
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
     * @param  list<ValueAxis>  $valueAxes  from `unitAxes()`; empty for the single default value axis
     */
    protected function cartesianOption(CategoryAxis $categories, array $series, bool $horizontal = false, array $valueAxes = []): Option
    {
        $axes = count($valueAxes) > 1 ? $valueAxes : [ValueAxis::make()];

        // Axes alternate between the two sides, the third and later ones stepping further out; give the plot room for them.
        $near = (int) ceil(count($axes) / 2);
        $far = intdiv(count($axes), 2);
        $extraNear = max(0, $near - 1) * self::AXIS_STEP;
        $extraFar = max(0, $far - 1) * self::AXIS_STEP;

        $grid = Grid::make()->containLabel()->left(8)->right(16)->bottom(8)->top(count($series) > 1 ? 44 : 16);
        if (count($axes) > 1) {
            $horizontal
                ? $grid->bottom(8 + $extraNear)->top(44 + $extraFar + 16)
                : $grid->left(8 + $extraNear)->right(16 + $extraFar + 8);
        }

        $option = Option::make()
            ->tooltip($this->axisTooltip())
            ->grid($grid)
            ->series(...$series);

        if (count($series) > 1) {
            $option->legend($this->scrollLegend());
        }

        return $horizontal
            ? $option->xAxis(...$axes)->yAxis($categories->inverse())
            : $option->xAxis($categories)->yAxis(...$axes);
    }

    /**
     * The distinct units of a set of series, in order of first appearance.
     *
     * @param  list<array{unit: string|null}>  $series
     * @return list<string|null>
     */
    protected function unitGroups(array $series): array
    {
        $units = [];
        foreach ($series as $item) {
            if (! in_array($item['unit'], $units, true)) {
                $units[] = $item['unit'];
            }
        }

        return $units;
    }

    /** The colour a series of a multi-axis chart is drawn in. */
    protected function seriesColor(int $index): string
    {
        return self::PALETTE[$index % count(self::PALETTE)];
    }

    /**
     * One value axis per unit: the first on the left (below, when horizontal),
     * the second on the right (above), the next ones alternating with a growing
     * `offset`. Each is named by its unit and takes the colour of its series
     * when it carries just one. Returns `[]` for a single unit, which keeps the
     * one plain value axis.
     *
     * @param  list<string|null>  $units  from `unitGroups()`
     * @param  list<string|null>  $seriesUnits  the unit of every series, in series order
     * @return list<ValueAxis>
     */
    protected function unitAxes(array $units, array $seriesUnits, bool $horizontal): array
    {
        if (count($units) < 2) {
            return [];
        }

        $axes = [];
        foreach ($units as $index => $unit) {
            $axis = ValueAxis::make()
                ->position($horizontal ? ($index % 2 === 0 ? 'bottom' : 'top') : ($index % 2 === 0 ? 'left' : 'right'))
                ->offset(intdiv($index, 2) * self::AXIS_STEP)
                ->alignTicks();

            if ($unit !== null) {
                $axis->name($unit);
            }

            $carried = array_keys($seriesUnits, $unit, true);
            if (count($carried) === 1) {
                $color = $this->seriesColor($carried[0]);
                $axis->axisLine(['show' => true, 'lineStyle' => ['color' => $color]])
                    ->axisLabel(Label::make()->color($color))
                    ->nameTextStyle(Label::make()->color($color));
            }

            $axes[] = $index > 0 ? $axis->splitLine(false) : $axis;
        }

        return $axes;
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
     * @return array{categories: list<string>, series: list<array{name: string, values: list<int|float|null>, unit: string|null}>}
     */
    protected function breakdown(ChartDataset $dataset): array
    {
        $x = $dataset->dimensions[0]->name;

        if (count($dataset->dimensions) === 2) {
            return [
                'categories' => $dataset->distinct($x),
                'series' => array_map(
                    static fn (array $series): array => $series + ['unit' => null],
                    $dataset->breakdown($x, $dataset->dimensions[1]->name, $dataset->measures[0]->name),
                ),
            ];
        }

        return [
            'categories' => $dataset->labels($x),
            'series' => array_map(
                static fn (DatasetMeasure $measure): array => ['name' => $measure->label, 'values' => $dataset->values($measure->name), 'unit' => $measure->unit],
                $dataset->measures,
            ),
        ];
    }
}
