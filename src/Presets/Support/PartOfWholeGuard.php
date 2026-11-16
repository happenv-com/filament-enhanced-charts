<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Support;

use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Presets\ChartPreset;
use Happenv\FilamentEnhancedCharts\Presets\EncodesPartOfWhole;

/** The one place that decides whether a preset's part-of-whole encoding collides with a multi-valued dimension. */
final class PartOfWholeGuard
{
    /** Whether the preset partitions a total by a dimension that is multi-valued in this shape. */
    public static function refuses(ChartPreset $preset, DatasetShape $shape): bool
    {
        if (! $preset instanceof EncodesPartOfWhole) {
            return false;
        }

        return array_any($preset->partitioningDimensions($shape), fn (int $index): bool => $shape->isMultiValuedAt($index));
    }
}
