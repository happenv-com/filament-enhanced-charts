<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Data;

use Happenv\FilamentEnhancedCharts\Enums\Additivity;
use Happenv\FilamentEnhancedCharts\Enums\MeasureRole;

/**
 * A numeric column of a `ChartDataset`: what is counted or summed per row (orders, revenue, …).
 * An optional `MeasureRole` names the statistic it carries (a boxplot's median, a candlestick's close).
 * An optional `unit` is free text a chart labels the measure with (`PLN`, `pcs`, `%`, `views`): measures in
 * two different units share a chart on two value axes, one per unit.
 * `additivity` says whether the values can be summed into a whole: `Additive` (the default) always,
 * `SemiAdditive` across non-time dimensions only (a stock level), `NonAdditive` never (a ratio, an average, a
 * distinct count). Presets that encode part-of-whole refuse a measure that does not add up — see "Data semantics"
 * in the README.
 */
final readonly class DatasetMeasure
{
    public function __construct(
        public string $name,
        public string $label,
        public ?MeasureRole $role = null,
        public ?string $unit = null,
        public Additivity $additivity = Additivity::Additive,
    ) {
        ChartDataset::assertValidName($name, 'measure');
    }

    /** A measure carrying a role, e.g. `DatasetMeasure::withRole(MeasureRole::Median, 'median', 'Median')`. */
    public static function withRole(MeasureRole $role, string $name, string $label): self
    {
        return new self($name, $label, $role);
    }

    /** The same measure with this additivity. */
    public function withAdditivity(Additivity $additivity): self
    {
        return new self($this->name, $this->label, $this->role, $this->unit, $additivity);
    }

    /** The same measure, summable over every dimension (the default). */
    public function additive(): self
    {
        return $this->withAdditivity(Additivity::Additive);
    }

    /** The same measure, summable across non-time dimensions only — a stock level, a balance. */
    public function semiAdditive(): self
    {
        return $this->withAdditivity(Additivity::SemiAdditive);
    }

    /** The same measure, never summable — a ratio, an average, a distinct count. */
    public function nonAdditive(): self
    {
        return $this->withAdditivity(Additivity::NonAdditive);
    }

    /**
     * `role`, `unit` and `additivity` are present only when the measure has one (additivity: when it is not
     * `Additive`), so a plain measure serialises exactly as it did before they existed.
     *
     * @return array{name: string, label: string, role?: string, unit?: string, additivity?: string}
     */
    public function toArray(): array
    {
        $measure = ['name' => $this->name, 'label' => $this->label];

        if ($this->role instanceof MeasureRole) {
            $measure['role'] = $this->role->value;
        }

        if ($this->unit !== null) {
            $measure['unit'] = $this->unit;
        }

        if ($this->additivity !== Additivity::Additive) {
            $measure['additivity'] = $this->additivity->value;
        }

        return $measure;
    }
}
