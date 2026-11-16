<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Exceptions;

use InvalidArgumentException;

/** A `ChartDataset` was built (or restored from an array) with a malformed definition or rows. */
final class InvalidChartDataset extends InvalidArgumentException
{
    public static function at(string $path, string $problem): self
    {
        return new self("Invalid chart dataset at [{$path}]: {$problem}.");
    }
}
