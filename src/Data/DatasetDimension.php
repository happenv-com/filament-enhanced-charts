<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Data;

use Happenv\FilamentEnhancedCharts\Enums\DimensionKind;

/**
 * A grouping column of a `ChartDataset`: what the rows are split by (channel, month, …).
 *
 * A dimension is *multi-valued* when one entity of the row grain carries several of its values (a product
 * in several categories, an order with several tags): the entity then appears once per value, so a measure
 * summed over the dimension does not add up to the whole. Presets that encode part-of-whole refuse such a
 * dimension — see "Data semantics" in the README.
 */
final readonly class DatasetDimension
{
    public function __construct(
        public string $name,
        public string $label,
        public DimensionKind $kind = DimensionKind::Category,
        public bool $multiValued = false,
    ) {
        ChartDataset::assertValidName($name, 'dimension');
    }

    public static function category(string $name, string $label, bool $multiValued = false): self
    {
        return new self($name, $label, DimensionKind::Category, $multiValued);
    }

    public static function time(string $name, string $label): self
    {
        return new self($name, $label, DimensionKind::Time);
    }

    /** Values are ISO 3166-1 alpha-2 country codes in uppercase (`PL`, `DE`). */
    public static function region(string $name, string $label, bool $multiValued = false): self
    {
        return new self($name, $label, DimensionKind::Region, $multiValued);
    }

    /** The same dimension, marked (or, with `false`, unmarked) as multi-valued relative to the row grain. */
    public function multiValued(bool $multiValued = true): self
    {
        return new self($this->name, $this->label, $this->kind, $multiValued);
    }

    /**
     * `multiValued` is present only when true, so a plain dimension serialises exactly as it did before it existed.
     *
     * @return array{name: string, label: string, kind: string, multiValued?: true}
     */
    public function toArray(): array
    {
        $dimension = ['name' => $this->name, 'label' => $this->label, 'kind' => $this->kind->value];

        if ($this->multiValued) {
            $dimension['multiValued'] = true;
        }

        return $dimension;
    }
}
