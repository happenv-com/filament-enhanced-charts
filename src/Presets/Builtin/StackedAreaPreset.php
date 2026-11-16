<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Option\Axis\CategoryAxis;
use Happenv\FilamentEnhancedCharts\Option\Series\LineSeries;
use Happenv\FilamentEnhancedCharts\Presets\Support\CartesianPreset;

final class StackedAreaPreset extends CartesianPreset
{
    public function name(): string
    {
        return 'stacked-area';
    }

    public function description(): string
    {
        return 'Shows how the total of a measure over an ordered dimension is composed of the values of a second dimension.';
    }

    public function requirement(): string
    {
        return self::BREAKDOWN;
    }

    public function accepts(DatasetShape $shape): bool
    {
        return $this->acceptsBreakdown($shape);
    }

    protected function categoryAxis(): CategoryAxis
    {
        return CategoryAxis::make()->boundaryGap(false);
    }

    protected function series(string $name, array $values): LineSeries
    {
        return LineSeries::make()->name($name)->stack('total')->areaStyle()->data($values);
    }
}
