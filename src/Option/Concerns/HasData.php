<?php

namespace Happenv\FilamentEnhancedCharts\Option\Concerns;

use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

/**
 * Flat `data` array shared by series that carry their points as a simple
 * list (line, bar, pie, …). Series without a flat shape (sankey, which
 * derives `data` from its nodes/links) do not use this trait.
 */
trait HasData
{
    /** @var array<mixed>|null */
    private ?array $data = null;

    /**
     * The series' data points, as a positional list. A keyed Collection
     * (`->pluck('total', 'month')`, a `groupBy` result) has its keys dropped —
     * ECharts reads `data` positionally. Pass scalars or `DataPoint`s, NOT a
     * `Collection<Model>` (each model would serialize to its full attribute
     * bag); project to the chart columns first.
     *
     * @param  iterable<mixed>  $data
     */
    public function data(iterable $data): static
    {
        $this->data = Normalize::list($data);

        return $this;
    }

    /**
     * The set `data` key, omitting it when unset.
     *
     * @return array<string, mixed>
     */
    protected function dataArray(): array
    {
        return $this->data !== null ? ['data' => Normalize::value($this->data)] : [];
    }
}
