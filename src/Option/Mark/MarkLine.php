<?php

namespace Happenv\FilamentEnhancedCharts\Option\Mark;

use BcMath\Number;
use Happenv\FilamentEnhancedCharts\Enums\Symbol;
use Happenv\FilamentEnhancedCharts\Option\Style\LineStyle;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

/**
 * Reference/target/stat lines drawn over a series (a threshold, an average,
 * a min/max marker) — set on a series via `->markLine()`.
 */
final class MarkLine extends Mark
{
    /** @var array<string, mixed>|null */
    private ?array $lineStyle = null;

    /** @var string|array<int, string>|null */
    private string | array | null $symbol = null;

    /** @var int|float|array<int, int|float>|null */
    private int | float | array | null $symbolSize = null;

    /** A horizontal line at a fixed y value. */
    public function at(int | float | string | Number $y, ?string $name = null): self
    {
        $entry = ['yAxis' => $y];
        if ($name !== null) {
            $entry['name'] = $name;
        }

        $this->data[] = $entry;

        return $this;
    }

    public function average(?string $name = 'avg'): self
    {
        return $this->stat('average', $name);
    }

    public function min(?string $name = 'min'): self
    {
        return $this->stat('min', $name);
    }

    public function max(?string $name = 'max'): self
    {
        return $this->stat('max', $name);
    }

    /** @param LineStyle|array<string, mixed> $lineStyle */
    public function lineStyle(LineStyle | array $lineStyle): self
    {
        $this->lineStyle = Normalize::arr($lineStyle);

        return $this;
    }

    /**
     * A single symbol for both ends, or a `[start, end]` pair (e.g.
     * `[Symbol::None, Symbol::Arrow]`).
     *
     * @param  Symbol|string|array<int, Symbol|string>  $symbol
     */
    public function symbol(Symbol | string | array $symbol): self
    {
        $this->symbol = is_array($symbol)
            ? array_map(Normalize::enum(...), $symbol)
            : Normalize::enum($symbol);

        return $this;
    }

    /** @param int|float|array<int, int|float> $size A fixed size for both ends, or a [start, end] pair. */
    public function symbolSize(int | float | array $size): self
    {
        $this->symbolSize = $size;

        return $this;
    }

    #[\Override]
    protected function markConfig(): array
    {
        $config = [];

        if ($this->lineStyle !== null) {
            $config['lineStyle'] = $this->lineStyle;
        }
        if ($this->symbol !== null) {
            $config['symbol'] = $this->symbol;
        }
        if ($this->symbolSize !== null) {
            $config['symbolSize'] = $this->symbolSize;
        }

        return $config;
    }
}
