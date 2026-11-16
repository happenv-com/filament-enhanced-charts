<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Support;

use Filament\Support\Facades\FilamentAsset;
use Happenv\FilamentEnhancedCharts\Option\Option;

/**
 * The maps the package itself ships, and which of them an option needs.
 *
 * A `map` series or `geo` component draws nothing until its GeoJSON is
 * registered with ECharts; the widget fetches whatever `getMaps()` returns
 * (name => URL) before the first paint. `for()` answers that for an option, so
 * a chart that uses no map asks Filament for no asset and a chart that does
 * pulls just the world map, once, on demand.
 */
final class ChartMaps
{
    /** The map name a preset's `map` series / `geo` component refers to. */
    public const string WORLD = 'filament-enhanced-charts-world';

    /** The Filament asset id (an ES module whose default export is the GeoJSON). */
    public const string WORLD_ASSET = 'filament-enhanced-charts-map-world';

    /**
     * The shipped maps an option uses, as `name => URL`: pass it as
     * `getMaps()`, or as the `maps` of the chart component.
     *
     * @param  Option|array<string, mixed>  $option
     * @return array<string, string>
     */
    public static function for(Option | array $option): array
    {
        $option = $option instanceof Option ? $option->toArray() : $option;

        if (! in_array(self::WORLD, self::referenced($option), true)) {
            return [];
        }

        return [self::WORLD => FilamentAsset::getAlpineComponentSrc(self::WORLD_ASSET, 'happenv/filament-enhanced-charts')];
    }

    /**
     * @param  array<string, mixed>  $option
     * @return list<string>
     */
    private static function referenced(array $option): array
    {
        $names = [];

        $series = $option['series'] ?? [];
        foreach (is_array($series) ? $series : [] as $item) {
            if (is_array($item) && ($item['type'] ?? null) === 'map' && is_string($item['map'] ?? null)) {
                $names[] = $item['map'];
            }
        }

        $geo = $option['geo'] ?? [];
        $geo = is_array($geo) && array_is_list($geo) ? $geo : [$geo];
        foreach ($geo as $item) {
            if (is_array($item) && is_string($item['map'] ?? null)) {
                $names[] = $item['map'];
            }
        }

        return $names;
    }
}
