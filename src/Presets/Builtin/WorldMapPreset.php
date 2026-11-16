<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Enums\DimensionKind;
use Happenv\FilamentEnhancedCharts\Option\Component\VisualMap;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\MapSeries;
use Happenv\FilamentEnhancedCharts\Presets\AbstractChartPreset;
use Happenv\FilamentEnhancedCharts\Support\ChartMaps;
use Happenv\FilamentEnhancedCharts\Support\Countries;

final class WorldMapPreset extends AbstractChartPreset
{
    public function name(): string
    {
        return 'world-map';
    }

    public function description(): string
    {
        return 'Shows how a measure varies between countries as a world map with every country coloured by its value.';
    }

    public function requirement(): string
    {
        return '1 region dimension (ISO 3166-1 alpha-2 country codes, e.g. PL) and 1 measure';
    }

    public function accepts(DatasetShape $shape): bool
    {
        return $shape->dimensionCount() === 1 && $shape->kindAt(0) === DimensionKind::Region && $shape->measures === 1;
    }

    protected function build(ChartDataset $dataset): Option
    {
        // Regions are matched by name, so data carries the (localised) country name; a code the map lacks is skipped.
        $data = [];
        foreach ($dataset->totals($dataset->dimensions[0]->name, $dataset->measures[0]->name) as $total) {
            if ($total['value'] !== null && Countries::has($total['name'])) {
                $data[] = ['name' => Countries::name($total['name']), 'value' => $total['value']];
            }
        }

        $values = array_column($data, 'value');
        $min = $values === [] ? 0 : min($values);
        $max = $values === [] ? 1 : max($values);

        return Option::make()
            ->tooltip($this->itemTooltip())
            ->visualMap(
                VisualMap::continuous()
                    ->min($min)
                    ->max($max > $min ? $max : $min + 1)
                    ->orient('horizontal')
                    ->left('center')
                    ->bottom(8)
                    ->calculable()
                    ->colors('#e0f2fe', '#0369a1'),
            )
            ->series(
                MapSeries::make()
                    ->name($dataset->measures[0]->label)
                    ->map(ChartMaps::WORLD)
                    ->nameProperty('iso')
                    ->nameMap(self::nameMap())
                    ->data($data),
            );
    }

    /**
     * Every country on the map by code, named in the app locale.
     *
     * @return array<string, string>
     */
    public static function nameMap(): array
    {
        $names = [];
        foreach (Countries::codes() as $code) {
            $names[$code] = Countries::name($code);
        }

        return $names;
    }
}
