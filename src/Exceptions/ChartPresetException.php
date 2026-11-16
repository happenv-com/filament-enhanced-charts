<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Exceptions;

use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Presets\ChartPreset;
use InvalidArgumentException;

/** A chart preset could not be registered, found, or applied to the given dataset. */
final class ChartPresetException extends InvalidArgumentException
{
    public static function invalidName(string $name): self
    {
        return new self("Chart preset name [{$name}] must be a non-empty kebab-case key such as [stacked-bar].");
    }

    public static function unknown(string $name): self
    {
        return new self("Unknown chart preset [{$name}].");
    }

    public static function notAccepted(ChartPreset $preset, DatasetShape $shape): self
    {
        return new self(sprintf(
            'Chart preset [%s] needs %s, but the dataset has %s.',
            $preset->name(),
            $preset->requirement(),
            $shape->describe(),
        ));
    }

    public static function unusableData(ChartPreset $preset, string $problem): self
    {
        return new self("Chart preset [{$preset->name()}] cannot draw this dataset: {$problem}.");
    }
}
