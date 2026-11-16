<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Enums\GraphLayout;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\GraphSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\Emphasis;
use Happenv\FilamentEnhancedCharts\Option\Style\Force;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;
use Happenv\FilamentEnhancedCharts\Option\Style\LineStyle;
use Happenv\FilamentEnhancedCharts\Presets\Support\FlowPreset;

final class GraphPreset extends FlowPreset
{
    private const int MIN_NODE = 14;

    private const int MAX_NODE = 56;

    public function name(): string
    {
        return 'graph';
    }

    public function description(): string
    {
        return 'Shows which values of one dimension are connected to which values of another as a force-directed network, with node size and link width proportional to the flow.';
    }

    public function requirement(): string
    {
        return '2 dimensions (category, time or region; the first is the source, the second the target) and 1 measure (the weight of the link; a node is as large as its links sum to)' . self::SINGLE_VALUED_DIMENSIONS;
    }

    /** A node is as large as the sum of its flows; a row counted under two values of either end inflates the totals. */
    public function partitioningDimensions(DatasetShape $shape): array
    {
        return [0, 1];
    }

    protected function build(ChartDataset $dataset): Option
    {
        $flows = $this->flows($dataset);

        $totals = [];
        foreach ($flows as $flow) {
            $totals[$flow['source']] = ($totals[$flow['source']] ?? 0) + $flow['value'];
            $totals[$flow['target']] = ($totals[$flow['target']] ?? 0) + $flow['value'];
        }
        $peakTotal = $totals === [] ? 1 : max($totals);
        $peakFlow = $flows === [] ? 1 : max(array_column($flows, 'value'));

        $nodes = [];
        foreach ($totals as $name => $total) {
            $nodes[] = [
                'name' => (string) $name,
                'value' => $total,
                // Area, not diameter, follows the total, so a node twice the flow looks twice as big.
                'symbolSize' => round(self::MIN_NODE + (self::MAX_NODE - self::MIN_NODE) * sqrt($total / $peakTotal), 1),
            ];
        }

        $links = array_map(
            static fn (array $flow): array => $flow + ['lineStyle' => ['width' => round(1 + 5 * $flow['value'] / $peakFlow, 1)]],
            $flows,
        );

        return Option::make()
            ->tooltip($this->itemTooltip())
            ->series(
                GraphSeries::make()
                    ->layout(GraphLayout::Force)
                    ->data($nodes)
                    ->links($links)
                    ->roam()
                    ->draggable()
                    ->edgeSymbol(['none', 'arrow'])
                    ->edgeSymbolSize([0, 8])
                    ->label(Label::make()->show()->position('right'))
                    ->lineStyle(LineStyle::make()->opacity(0.5)->curveness(0.15))
                    ->force(Force::make()->repulsion(180)->edgeLength([60, 160])->gravity(0.08))
                    ->emphasis(Emphasis::make()->focus('adjacency')),
            );
    }
}
