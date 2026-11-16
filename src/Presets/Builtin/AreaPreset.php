<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Option\Axis\CategoryAxis;
use Happenv\FilamentEnhancedCharts\Option\Series\LineSeries;
use Happenv\FilamentEnhancedCharts\Presets\Support\CartesianPreset;

final class AreaPreset extends CartesianPreset
{
    public function name(): string
    {
        return 'area';
    }

    public function description(): string
    {
        return 'Shows the volume of one or more measures along an ordered dimension as filled lines.';
    }

    public function requirement(): string
    {
        return self::ONE_DIMENSION_MEASURES;
    }

    public function accepts(DatasetShape $shape): bool
    {
        return $this->oneDimensionMeasures($shape);
    }

    protected function categoryAxis(): CategoryAxis
    {
        return CategoryAxis::make()->boundaryGap(false);
    }

    protected function series(string $name, array $values): LineSeries
    {
        return LineSeries::make()->name($name)->areaStyle()->data($values);
    }
}
