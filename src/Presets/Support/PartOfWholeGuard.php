<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Support;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Enums\Additivity;
use Happenv\FilamentEnhancedCharts\Enums\DimensionKind;
use Happenv\FilamentEnhancedCharts\Exceptions\ChartPresetException;
use Happenv\FilamentEnhancedCharts\Presets\ChartPreset;
use Happenv\FilamentEnhancedCharts\Presets\ChartPresets;
use Happenv\FilamentEnhancedCharts\Presets\EncodesPartOfWhole;
use Happenv\FilamentEnhancedCharts\Presets\StacksMeasures;

/**
 * The one place that decides whether a preset's part-of-whole encoding collides with the data: a multi-valued
 * dimension it partitions by, or a measure that does not add up along it (a non-additive one anywhere, a
 * semi-additive one across a time dimension).
 */
final class PartOfWholeGuard
{
    private const int SUGGESTIONS = 6;

    /** Whether the preset partitions a total in a way this shape's dimensions or measures cannot support. */
    public static function refuses(ChartPreset $preset, DatasetShape $shape): bool
    {
        $multiValued = $preset instanceof EncodesPartOfWhole
            && array_any($preset->partitioningDimensions($shape), fn (int $index): bool => $shape->isMultiValuedAt($index));

        return $multiValued || self::unsummableMeasure($preset, $shape) !== null;
    }

    /**
     * The first measure the preset would add up (across the dimensions it partitions by, or by stacking measures)
     * although it does not add up, and the time dimension it would add it up across (`null` for a non-additive measure, which adds up along no dimension); `null` when
     * every measure is fine.
     *
     * @return array{measure: int, dimension: int|null}|null
     */
    public static function unsummableMeasure(ChartPreset $preset, DatasetShape $shape): ?array
    {
        if (! $shape->hasNonAdditiveMeasure()) {
            return null;
        }

        // Measures stacked into a total: a ratio or an average has no meaningful sum, whatever the dimensions.
        if ($preset instanceof StacksMeasures && $preset->stacksMeasures($shape)) {
            $stacked = array_search(Additivity::NonAdditive, $shape->additivity, true);

            if ($stacked !== false) {
                return ['measure' => $stacked, 'dimension' => null];
            }
        }

        $partitioning = $preset instanceof EncodesPartOfWhole ? $preset->partitioningDimensions($shape) : [];
        if ($partitioning === []) {
            return null;
        }

        $time = array_find($partitioning, static fn (int $index): bool => $shape->kindAt($index) === DimensionKind::Time);

        foreach ($shape->additivity as $index => $additivity) {
            if ($additivity === Additivity::NonAdditive) {
                return ['measure' => $index, 'dimension' => null];
            }

            if ($additivity === Additivity::SemiAdditive && $time !== null) {
                return ['measure' => $index, 'dimension' => $time];
            }
        }

        return null;
    }

    /**
     * The exception for a dataset the preset refuses: a translated reason naming the measure (and suggesting
     * presets that split no total) when it is additivity, the generic "needs … but the dataset has …" otherwise.
     *
     * @param  iterable<ChartPreset>|null  $candidates  where to look for the suggested presets; the built-ins when omitted
     */
    public static function exceptionFor(ChartPreset $preset, ChartDataset $dataset, ?iterable $candidates = null): ChartPresetException
    {
        $shape = $dataset->shape();
        $unsummable = self::unsummableMeasure($preset, $shape);

        if ($unsummable === null) {
            return ChartPresetException::notAccepted($preset, $shape);
        }

        $measure = $dataset->measures[$unsummable['measure']];
        $suggestions = [];
        foreach ($candidates ?? ChartPresets::builtIn() as $candidate) {
            if (count($suggestions) < self::SUGGESTIONS && $candidate->accepts($shape) && ! self::partitions($candidate, $shape)) {
                $suggestions[] = $candidate->name();
            }
        }

        return ChartPresetException::notAdditive(
            $preset,
            $measure->label,
            $unsummable['dimension'] === null ? null : $dataset->dimensions[$unsummable['dimension']]->label,
            $suggestions,
        );
    }

    private static function partitions(ChartPreset $preset, DatasetShape $shape): bool
    {
        return $preset instanceof EncodesPartOfWhole && $preset->partitioningDimensions($shape) !== [];
    }
}
