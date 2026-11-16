<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Enums\DimensionKind;
use Happenv\FilamentEnhancedCharts\Enums\MeasureRole;
use Happenv\FilamentEnhancedCharts\Option\Axis\CategoryAxis;
use Happenv\FilamentEnhancedCharts\Option\Axis\ValueAxis;
use Happenv\FilamentEnhancedCharts\Option\Component\Grid;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\CandlestickSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;
use Happenv\FilamentEnhancedCharts\Presets\AbstractChartPreset;

final class CandlestickPreset extends AbstractChartPreset
{
    /** The order ECharts reads a candle in. */
    private const array ROLES = [MeasureRole::Open, MeasureRole::Close, MeasureRole::Low, MeasureRole::High];

    public function name(): string
    {
        return 'candlestick';
    }

    public function description(): string
    {
        return 'Shows how a value moved within each period as a candle from its opening to its closing value with the lowest and highest value as wicks, green when it rose and red when it fell.';
    }

    public function requirement(): string
    {
        return '1 time dimension (every value is one candle) and exactly 4 measures carrying the roles open, close, low and high, in any order';
    }

    public function accepts(DatasetShape $shape): bool
    {
        return $this->oneDimensionMeasures($shape, 4, 4)
            && $shape->kindAt(0) === DimensionKind::Time
            && $shape->hasExactlyRoles(...self::ROLES);
    }

    protected function build(ChartDataset $dataset): Option
    {
        $columns = array_map(
            $dataset->valuesOfRole(...),
            self::ROLES,
        );

        // A candle needs all four values: a row missing one cannot be drawn and is left out.
        $periods = [];
        $candles = [];
        foreach ($dataset->labels($dataset->dimensions[0]->name) as $row => $label) {
            $candle = array_column($columns, $row);

            if (! in_array(null, $candle, true)) {
                $periods[] = $label;
                $candles[] = $candle;
            }
        }

        return Option::make()
            ->tooltip($this->axisTooltip())
            ->grid(Grid::make()->containLabel()->left(8)->right(16)->top(16)->bottom(8))
            ->xAxis(CategoryAxis::make()->data($periods))
            ->yAxis(ValueAxis::make()->scale())
            ->series(
                CandlestickSeries::make()->data($candles)->itemStyle(
                    ItemStyle::make()->color('#16a34a')->borderColor('#16a34a')->color0('#dc2626')->borderColor0('#dc2626'),
                ),
            );
    }
}
