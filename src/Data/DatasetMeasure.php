<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Data;

/** A numeric column of a `ChartDataset`: what is counted or summed per row (orders, revenue, …). */
final readonly class DatasetMeasure
{
    public function __construct(
        public string $name,
        public string $label,
    ) {
        ChartDataset::assertValidName($name, 'measure');
    }

    /** @return array{name: string, label: string} */
    public function toArray(): array
    {
        return ['name' => $this->name, 'label' => $this->label];
    }
}
