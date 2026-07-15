<?php

namespace Happenv\FilamentEnhancedCharts\Option\Mark;

use Happenv\FilamentEnhancedCharts\Option\Component\Tooltip;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Illuminate\Support\Traits\Conditionable;

/**
 * Shared skeleton of the three series marks (MarkLine, MarkPoint, MarkArea):
 * appended `data` entries, label/silent/tooltip, and the raw escape hatch.
 * Concrete marks add their own entry helpers (`at()`, `band()`, stats) and
 * style keys via `markConfig()`.
 */
abstract class Mark implements Node
{
    use Conditionable;
    use HasRaw;

    /** @var array<mixed> */
    protected array $data = [];

    /** @var array<string, mixed>|null */
    private ?array $label = null;

    private ?bool $silent = null;

    /** @var array<string, mixed>|null */
    private ?array $tooltip = null;

    public static function make(): static
    {
        return new static;
    }

    /**
     * Append entries to the mark's data. NOTE: unlike `Series::data()` (which
     * replaces), mark data APPENDS — consistent with the `at()`/`band()`/stat
     * helpers, which also append one entry per call.
     *
     * @param  iterable<mixed>  $entries
     */
    public function data(iterable $entries): static
    {
        $this->data = array_merge($this->data, Normalize::iterable($entries));

        return $this;
    }

    /** @param Label|array<string, mixed> $label */
    public function label(Label | array $label): static
    {
        $this->label = Normalize::arr($label);

        return $this;
    }

    /** Disable mouse/touch events on the mark so it doesn't intercept hover/click. */
    public function silent(bool $silent = true): static
    {
        $this->silent = $silent;

        return $this;
    }

    /** @param Tooltip|array<string, mixed> $tooltip A tooltip config override for this mark. */
    public function tooltip(Tooltip | array $tooltip): static
    {
        $this->tooltip = Normalize::arr($tooltip);

        return $this;
    }

    final public function toArray(): array
    {
        $mark = ['data' => Normalize::value($this->data)];

        $mark = array_merge($mark, $this->markConfig());

        if ($this->label !== null) {
            $mark['label'] = $this->label;
        }
        if ($this->silent !== null) {
            $mark['silent'] = $this->silent;
        }
        if ($this->tooltip !== null) {
            $mark['tooltip'] = Normalize::value($this->tooltip);
        }

        return $this->mergeRaw($mark);
    }

    /**
     * The concrete mark's own keys (lineStyle/itemStyle/symbol…), set ones only.
     *
     * @return array<string, mixed>
     */
    protected function markConfig(): array
    {
        return [];
    }

    /** Append a computed-statistic entry ('average'|'min'|'max'). */
    protected function stat(string $type, ?string $name): static
    {
        $entry = ['type' => $type];
        if ($name !== null) {
            $entry['name'] = $name;
        }

        $this->data[] = $entry;

        return $this;
    }
}
