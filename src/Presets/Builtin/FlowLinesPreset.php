<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Enums\DimensionKind;
use Happenv\FilamentEnhancedCharts\Option\Component\Geo;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\LinesSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\ScatterSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;
use Happenv\FilamentEnhancedCharts\Option\Style\LinesEffect;
use Happenv\FilamentEnhancedCharts\Option\Style\LineStyle;
use Happenv\FilamentEnhancedCharts\Presets\Support\FlowPreset;
use Happenv\FilamentEnhancedCharts\Support\ChartMaps;
use Happenv\FilamentEnhancedCharts\Support\Countries;

final class FlowLinesPreset extends FlowPreset
{
    public function name(): string
    {
        return 'flow-lines';
    }

    public function description(): string
    {
        return 'Shows how a quantity flows between countries as animated lines on a world map from the origin to the destination country, the line width proportional to the flow.';
    }

    public function requirement(): string
    {
        return '2 region dimensions (ISO 3166-1 alpha-2 country codes; the first is the origin, the second the destination) and 1 measure';
    }

    protected function acceptsStructure(DatasetShape $shape): bool
    {
        return $shape->dimensionCount() === 2
            && $shape->kindAt(0) === DimensionKind::Region
            && $shape->kindAt(1) === DimensionKind::Region
            && $shape->measures === 1;
    }

    protected function build(ChartDataset $dataset): Option
    {
        // A flow whose end the map does not know has nowhere to be drawn and is skipped.
        $flows = array_values(array_filter(
            $this->flows($dataset),
            static fn (array $flow): bool => Countries::has($flow['source']) && Countries::has($flow['target']),
        ));
        $peak = $flows === [] ? 1 : max(array_column($flows, 'value'));

        $lines = [];
        $totals = [];
        foreach ($flows as $flow) {
            $lines[] = [
                'name' => Countries::name($flow['source']) . ' → ' . Countries::name($flow['target']),
                'value' => $flow['value'],
                'coords' => [Countries::centroid($flow['source']), Countries::centroid($flow['target'])],
                'lineStyle' => ['width' => round(1 + 5 * $flow['value'] / $peak, 1)],
            ];
            $totals[$flow['source']] = ($totals[$flow['source']] ?? 0) + $flow['value'];
            $totals[$flow['target']] = ($totals[$flow['target']] ?? 0) + $flow['value'];
        }

        $peakTotal = $totals === [] ? 1 : max($totals);
        $ends = [];
        foreach ($totals as $code => $total) {
            $ends[] = [
                'name' => Countries::name((string) $code),
                'value' => [...(Countries::centroid((string) $code) ?? []), $total],
                'symbolSize' => round(6 + 10 * sqrt($total / $peakTotal), 1),
            ];
        }

        return Option::make()
            ->tooltip($this->itemTooltip())
            ->geo(
                Geo::make()
                    ->map(ChartMaps::WORLD)
                    ->nameProperty('iso')
                    ->nameMap(WorldMapPreset::nameMap())
                    ->itemStyle(ItemStyle::make()->areaColor('#e5e7eb')->borderColor('#9ca3af')),
            )
            ->series(
                ScatterSeries::make()
                    ->coordinateSystem('geo')
                    ->data($ends)
                    ->itemStyle(ItemStyle::make()->color('#0369a1')),
                LinesSeries::make()
                    ->name($dataset->measures[0]->label)
                    ->coordinateSystem('geo')
                    ->data($lines)
                    ->lineStyle(LineStyle::make()->color('#0369a1')->opacity(0.6)->curveness(0.2))
                    ->effect(LinesEffect::make()->show()->period(6)->trailLength(0.1)->symbol('arrow')->symbolSize(5)),
            );
    }
}
