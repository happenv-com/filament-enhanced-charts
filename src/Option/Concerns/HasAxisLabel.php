<?php

namespace Happenv\FilamentEnhancedCharts\Option\Concerns;

use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

/**
 * The `axisLabel` sub-config, with one signature for every axis-like builder:
 * a string/RawJs is formatter shorthand, a Label builder or array is the full
 * config (nested RawJs/BcMath values become client-side markers).
 */
trait HasAxisLabel
{
    /** @var array<string, mixed>|null */
    private ?array $axisLabel = null;

    /**
     * The tick labels. A bare string is a literal ECharts template
     * (`'{value} °C'`), a RawJs is an executable formatter; a `Label`
     * builder or array configures the whole axisLabel.
     *
     * @param  Label|RawJs|string|array<string, mixed>  $axisLabel
     */
    public function axisLabel(Label | RawJs | string | array $axisLabel): static
    {
        $this->axisLabel = $axisLabel instanceof RawJs || is_string($axisLabel)
            ? ['formatter' => Normalize::formatter($axisLabel)]
            : Normalize::value(Normalize::arr($axisLabel));

        return $this;
    }

    /**
     * The set axisLabel key, omitting it when unset.
     *
     * @return array<string, mixed>
     */
    protected function axisLabelArray(): array
    {
        return $this->axisLabel !== null ? ['axisLabel' => $this->axisLabel] : [];
    }
}
