<?php

namespace Happenv\FilamentEnhancedCharts\Option\Mark;

use Happenv\FilamentEnhancedCharts\Enums\Symbol;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

/**
 * Peak/trough/annotation markers drawn over a series — set on a series via
 * `->markPoint()`.
 */
final class MarkPoint extends Mark
{
    /** @var array<string, mixed>|null */
    private ?array $itemStyle = null;

    private ?string $symbol = null;

    /** @var int|float|array<int, int|float>|null */
    private int | float | array | null $symbolSize = null;

    /** @var array<int, int|float>|null */
    private ?array $symbolOffset = null;

    public function max(?string $name = 'max'): self
    {
        return $this->stat('max', $name);
    }

    public function min(?string $name = 'min'): self
    {
        return $this->stat('min', $name);
    }

    /** @param array<int, mixed> $coord */
    public function at(array $coord, ?string $name = null): self
    {
        $entry = ['coord' => $coord];
        if ($name !== null) {
            $entry['name'] = $name;
        }

        $this->data[] = $entry;

        return $this;
    }

    /** @param ItemStyle|array<string, mixed> $itemStyle */
    public function itemStyle(ItemStyle | array $itemStyle): self
    {
        $this->itemStyle = Normalize::arr($itemStyle);

        return $this;
    }

    public function symbol(Symbol | string $symbol): self
    {
        $this->symbol = Normalize::enum($symbol);

        return $this;
    }

    /** @param int|float|array<int, int|float> $size A fixed size, or a [width, height] pair. */
    public function symbolSize(int | float | array $size): self
    {
        $this->symbolSize = $size;

        return $this;
    }

    /** @param array<int, int|float> $offset A [x, y] pixel offset from the symbol's anchor point. */
    public function symbolOffset(array $offset): self
    {
        $this->symbolOffset = $offset;

        return $this;
    }

    #[\Override]
    protected function markConfig(): array
    {
        $config = [];

        if ($this->itemStyle !== null) {
            $config['itemStyle'] = $this->itemStyle;
        }
        if ($this->symbol !== null) {
            $config['symbol'] = $this->symbol;
        }
        if ($this->symbolSize !== null) {
            $config['symbolSize'] = $this->symbolSize;
        }
        if ($this->symbolOffset !== null) {
            $config['symbolOffset'] = $this->symbolOffset;
        }

        return $config;
    }
}
