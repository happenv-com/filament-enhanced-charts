<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Data;

use Happenv\FilamentEnhancedCharts\Enums\MeasureRole;

/**
 * A numeric column of a `ChartDataset`: what is counted or summed per row (orders, revenue, …).
 * An optional `MeasureRole` names the statistic it carries (a boxplot's median, a candlestick's close).
 * An optional `unit` is free text a chart labels the measure with (`PLN`, `pcs`, `%`, `views`): measures in
 * two different units share a chart on two value axes, one per unit.
 */
final readonly class DatasetMeasure
{
    public function __construct(
        public string $name,
        public string $label,
        public ?MeasureRole $role = null,
        public ?string $unit = null,
    ) {
        ChartDataset::assertValidName($name, 'measure');
    }

    /** A measure carrying a role, e.g. `DatasetMeasure::withRole(MeasureRole::Median, 'median', 'Median')`. */
    public static function withRole(MeasureRole $role, string $name, string $label): self
    {
        return new self($name, $label, $role);
    }

    /**
     * `role` and `unit` are present only when the measure has one, so a plain
     * measure serialises exactly as it did before they existed.
     *
     * @return array{name: string, label: string, role?: string, unit?: string}
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

        return $measure;
    }
}
