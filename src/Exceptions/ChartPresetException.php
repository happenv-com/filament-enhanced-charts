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

    /**
     * A part-of-whole preset was given a measure that does not add up: `$timeDimension` is the label of the
     * time dimension a semi-additive measure would be summed across, `null` for a non-additive measure.
     * The message is translated (`filament-enhanced-charts::additivity.*`).
     *
     * @param  list<string>  $suggestions  names of presets that split no total
     */
    public static function notAdditive(ChartPreset $preset, string $measure, ?string $timeDimension, array $suggestions): self
    {
        $message = $timeDimension === null
            ? __('filament-enhanced-charts::additivity.non_additive', ['preset' => $preset->name(), 'measure' => $measure])
            : __('filament-enhanced-charts::additivity.semi_additive', ['preset' => $preset->name(), 'measure' => $measure, 'dimension' => $timeDimension]);

        if ($suggestions !== []) {
            $message .= ' ' . __('filament-enhanced-charts::additivity.suggestion', ['presets' => implode(', ', $suggestions)]);
        }

        return new self($message);
    }

    public static function unusableData(ChartPreset $preset, string $problem): self
    {
        return new self("Chart preset [{$preset->name()}] cannot draw this dataset: {$problem}.");
    }
}
