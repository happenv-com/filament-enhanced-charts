<?php

namespace Happenv\FilamentEnhancedCharts\Option\Concerns;

use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Enums\Symbol;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

/**
 * Marker shape/size shared by series that draw a symbol per data point
 * (line, scatter, …).
 */
trait HasSymbol
{
    private ?string $symbol = null;

    /** @var int|float|string|array<mixed>|null */
    private int | float | string | array | null $symbolSize = null;

    public function symbol(Symbol | string $symbol): static
    {
        $this->symbol = $symbol instanceof Symbol ? $symbol->value : $symbol;

        return $this;
    }

    /**
     * A fixed size (line/scatter), a percent/pixel `[width, height]` pair —
     * pictorial-bar accepts `['100%', '50%']` relative to its bounding data —
     * or a RawJs callback computing the size per data point.
     *
     * @param  int|float|string|array<int, int|string>|RawJs  $size
     */
    public function symbolSize(int | float | string | array | RawJs $size): static
    {
        $this->symbolSize = Normalize::value($size);

        return $this;
    }

    /**
     * The set symbol/symbolSize keys, omitting unset ones.
     *
     * @return array<string, mixed>
     */
    protected function symbolConfig(): array
    {
        return array_filter(
            ['symbol' => $this->symbol, 'symbolSize' => $this->symbolSize],
            static fn (mixed $value): bool => $value !== null,
        );
    }
}
