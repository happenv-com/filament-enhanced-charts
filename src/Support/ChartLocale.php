<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Support;

/**
 * The ECharts locale for the app locale: month and day names, toolbox, legend
 * and aria strings from resources/lang/{locale}/locale.php, laid over the
 * English ones so a partial (e.g. published and trimmed) translation never
 * leaves a string blank.
 */
final class ChartLocale
{
    /** @return array{name: string, strings: array<string, mixed>} */
    public static function current(): array
    {
        $locale = app()->getLocale();

        return [
            // ECharts registers locales by name; 'pt_BR' becomes 'PT-BR'.
            'name' => strtoupper(str_replace('_', '-', $locale)),
            'strings' => array_replace_recursive(self::strings('en'), self::strings($locale)),
        ];
    }

    /**
     * Only the time names — enough for a chart in a table cell, which has no
     * toolbox, legend selector or aria description, without repeating the
     * whole locale object in every row.
     *
     * @return array{name: string, strings: array<string, mixed>}
     */
    public static function currentTime(): array
    {
        $locale = self::current();

        return ['name' => $locale['name'], 'strings' => ['time' => $locale['strings']['time'] ?? []]];
    }

    /** @return array<string, mixed> */
    private static function strings(string $locale): array
    {
        $strings = __('filament-enhanced-charts::locale', [], $locale);

        return is_array($strings) ? $strings : [];
    }
}
