<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Option\Series;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasData;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRoam;

/**
 * A choropleth map series (`series.map`): colors/values regions of a
 * registered map by name. The map itself (GeoJSON) must be registered
 * client-side via `echarts.registerMap()` before this series renders — see
 * `map()`.
 */
final class MapSeries extends Series
{
    use HasData;
    use HasRoam;

    private ?string $map = null;

    private ?string $nameProperty = null;

    private int | float | null $zoom = null;

    /** @var array<int, string|int>|null */
    private ?array $center = null;

    protected function type(): string
    {
        return 'map';
    }

    /**
     * The registered map name this series renders (e.g. 'USA', 'world').
     * Must be registered client-side via `echarts.registerMap(name, geoJson)`
     * before this option is applied.
     */
    public function map(string $mapName): static
    {
        $this->map = $mapName;

        return $this;
    }

    /** The data-item key that names a region when matching the registered map's GeoJSON features. */
    public function nameProperty(string $nameProperty): static
    {
        $this->nameProperty = $nameProperty;

        return $this;
    }

    /** Current zoom level of the map. Default is 1. */
    public function zoom(int | float $zoom): static
    {
        $this->zoom = $zoom;

        return $this;
    }

    /** @param array<int, string|int> $center Current center position, as [x, y]. */
    public function center(array $center): static
    {
        $this->center = $center;

        return $this;
    }

    #[\Override]
    protected function build(): array
    {
        $series = array_merge(parent::build(), $this->dataArray());

        if ($this->map !== null) {
            $series['map'] = $this->map;
        }
        $series = array_merge($series, $this->roamArray());
        if ($this->nameProperty !== null) {
            $series['nameProperty'] = $this->nameProperty;
        }
        if ($this->zoom !== null) {
            $series['zoom'] = $this->zoom;
        }
        if ($this->center !== null) {
            $series['center'] = $this->center;
        }

        return $series;
    }
}
