<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Option\Series\BarSeries;
use Happenv\FilamentEnhancedCharts\Presets\Support\CartesianPreset;

final class GroupedBarPreset extends CartesianPreset
{
    public function name(): string
    {
        return 'grouped-bar';
    }

    public function description(): string
    {
        return 'Compares a measure across one dimension while splitting every group by a second dimension into side-by-side bars.';
    }

    public function requirement(): string
    {
        return self::BREAKDOWN;
    }

    protected function acceptsStructure(DatasetShape $shape): bool
    {
        return $this->acceptsBreakdown($shape);
    }

    protected function series(string $name, array $values, int $axis = 0): BarSeries
    {
        return BarSeries::make()->name($name)->data($values);
    }
}
