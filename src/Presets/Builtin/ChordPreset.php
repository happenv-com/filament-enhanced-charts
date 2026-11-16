<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets\Builtin;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\ChordSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\Emphasis;
use Happenv\FilamentEnhancedCharts\Presets\Support\FlowPreset;

final class ChordPreset extends FlowPreset
{
    public function name(): string
    {
        return 'chord';
    }

    public function description(): string
    {
        return 'Shows how strongly the values of one dimension are tied to the values of another as ribbons between arcs around a circle, the ribbon width proportional to the flow.';
    }

    public function requirement(): string
    {
        return '2 dimensions (category, time or region; the first is the source, the second the target) and 1 measure (the weight of the ribbon; an arc is as long as its ribbons sum to)' . self::SINGLE_VALUED_DIMENSIONS;
    }

    /** An arc is as large as the sum of its flows; a row counted under two values of either end inflates the totals. */
    public function partitioningDimensions(DatasetShape $shape): array
    {
        return [0, 1];
    }

    protected function build(ChartDataset $dataset): Option
    {
        return Option::make()
            ->tooltip($this->itemTooltip())
            ->series(
                ChordSeries::make()
                    ->links($this->flows($dataset))
                    ->radius(['62%', '72%'])
                    ->center(['50%', '50%'])
                    ->padAngle(3)
                    ->emphasis(Emphasis::make()->focus('adjacency')),
            );
    }
}
