<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Support;

use Locale;

/**
 * The countries the bundled world map knows, by ISO 3166-1 alpha-2 code: their
 * names (in the app locale when `ext-intl` has one, else English) and the point
 * a flow is drawn to or from. Source: Natural Earth 1:110m, public domain.
 */
final class Countries
{
    /** @var array<string, array{0: string, 1: float|int, 2: float|int}>|null */
    private static ?array $table = null;

    public static function has(string $code): bool
    {
        return isset(self::table()[$code]);
    }

    /** @return list<string> */
    public static function codes(): array
    {
        return array_map(strval(...), array_keys(self::table()));
    }

    /** The country's name in the locale (the app locale by default); the code itself when it is not on the map. */
    public static function name(string $code, ?string $locale = null): string
    {
        $english = self::table()[$code][0] ?? null;

        if ($english === null) {
            return $code;
        }

        $localised = Locale::getDisplayRegion('-' . $code, $locale ?? app()->getLocale());

        // intl answers with the code itself when its data lacks the region or the language.
        return $localised !== '' && $localised !== $code ? $localised : $english;
    }

    /** @return array{0: float|int, 1: float|int}|null longitude and latitude */
    public static function centroid(string $code): ?array
    {
        $country = self::table()[$code] ?? null;

        return $country === null ? null : [$country[1], $country[2]];
    }

    /** @return array<string, array{0: string, 1: float|int, 2: float|int}> */
    private static function table(): array
    {
        return self::$table ??= require __DIR__ . '/../../resources/geo/countries.php';
    }
}
