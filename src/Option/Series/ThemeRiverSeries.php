<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Option\Series;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasData;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLayout;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

/**
 * A stacked stream graph showing how a set of themes evolve over time. Binds
 * to a `singleAxis` (see `Option\Component\SingleAxis`) rather than
 * x/yAxis, so it carries `singleAxisIndex` instead of the cartesian
 * `xAxisIndex`/`yAxisIndex` from the base `Series`.
 */
final class ThemeRiverSeries extends Series
{
    use HasData;
    use HasLayout;

    /** @var list<int|string>|null */
    private ?array $boundaryGap = null;

    protected function type(): string
    {
        return 'themeRiver';
    }

    /**
     * The gap left in the axis's orthogonal orientation, e.g. `['10%', '10%']`.
     *
     * @param  array<int, int|string>  $gap
     */
    public function boundaryGap(array $gap): static
    {
        $this->boundaryGap = $gap;

        return $this;
    }

    #[\Override]
    protected function build(): array
    {
        $series = array_merge(parent::build(), $this->dataArray());

        if ($this->boundaryGap !== null) {
            $series['boundaryGap'] = Normalize::value($this->boundaryGap);
        }

        return array_merge($series, $this->boxLayoutArray());
    }
}
