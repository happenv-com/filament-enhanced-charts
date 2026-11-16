<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Data;

use Happenv\FilamentEnhancedCharts\Enums\DimensionKind;

/** A grouping column of a `ChartDataset`: what the rows are split by (channel, month, …). */
final readonly class DatasetDimension
{
    public function __construct(
        public string $name,
        public string $label,
        public DimensionKind $kind = DimensionKind::Category,
    ) {
        ChartDataset::assertValidName($name, 'dimension');
    }

    public static function category(string $name, string $label): self
    {
        return new self($name, $label, DimensionKind::Category);
    }

    public static function time(string $name, string $label): self
    {
        return new self($name, $label, DimensionKind::Time);
    }

    /** Values are ISO 3166-1 alpha-2 country codes in uppercase (`PL`, `DE`). */
    public static function region(string $name, string $label): self
    {
        return new self($name, $label, DimensionKind::Region);
    }

    /** @return array{name: string, label: string, kind: string} */
    public function toArray(): array
    {
        return ['name' => $this->name, 'label' => $this->label, 'kind' => $this->kind->value];
    }
}
