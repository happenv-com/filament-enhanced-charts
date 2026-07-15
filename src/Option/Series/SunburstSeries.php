<?php

namespace Happenv\FilamentEnhancedCharts\Option\Series;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasData;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRadius;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

final class SunburstSeries extends Series
{
    use HasData;
    use HasRadius;

    /** @var array<int, mixed>|null */
    private ?array $levels = null;

    private ?string $sort = null;

    protected function type(): string
    {
        return 'sunburst';
    }

    /**
     * Per-depth level overrides (radius/itemStyle/label per ring), e.g.
     * `[['r0' => '15%', 'r' => '35%'], ['r0' => '35%', 'r' => '70%']]`.
     * No dedicated builder — pass the array directly.
     *
     * @param  array<int, mixed>  $levels
     */
    public function levels(array $levels): static
    {
        $this->levels = Normalize::value($levels);

        return $this;
    }

    /** Sort order of sibling nodes: `'desc'`/`'asc'`, or `null` to clear it. */
    public function sort(?string $sort): static
    {
        $this->sort = $sort;

        return $this;
    }

    #[\Override]
    protected function build(): array
    {
        $series = array_merge(parent::build(), $this->dataArray(), $this->radiusLayout());
        if ($this->levels !== null) {
            $series['levels'] = $this->levels;
        }
        if ($this->sort !== null) {
            $series['sort'] = $this->sort;
        }

        return $series;
    }
}
