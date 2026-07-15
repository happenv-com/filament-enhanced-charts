<?php

namespace Happenv\FilamentEnhancedCharts\Option\Mark;

use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

/**
 * Highlight bands drawn over a series (e.g. a date range or a target zone) —
 * set on a series via `->markArea()`. ECharts represents each band as a pair
 * of endpoint descriptors, so `->band()` appends one pair per call.
 */
final class MarkArea extends Mark
{
    /** @var array<string, mixed>|null */
    private ?array $itemStyle = null;

    /** A highlighted band spanning from one x value to another. */
    public function band(int | float | string $from, int | float | string $to, ?string $name = null): self
    {
        $start = ['xAxis' => $from];
        if ($name !== null) {
            $start['name'] = $name;
        }

        $this->data[] = [$start, ['xAxis' => $to]];

        return $this;
    }

    /** @param ItemStyle|array<string, mixed> $itemStyle */
    public function itemStyle(ItemStyle | array $itemStyle): self
    {
        $this->itemStyle = Normalize::arr($itemStyle);

        return $this;
    }

    #[\Override]
    protected function markConfig(): array
    {
        return $this->itemStyle !== null ? ['itemStyle' => $this->itemStyle] : [];
    }
}
