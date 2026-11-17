<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetDimension;
use Happenv\FilamentEnhancedCharts\Data\DatasetMeasure;
use Happenv\FilamentEnhancedCharts\Presets\AbstractChartPreset;
use Happenv\FilamentEnhancedCharts\Presets\ChartPresets;
use Happenv\FilamentEnhancedCharts\Support\NumberFormat;

covers(AbstractChartPreset::class);

/** @param list<DatasetMeasure> $measures */
function formatted(array $measures): ChartDataset
{
    $rows = [];
    foreach (['Allegro', 'Amazon'] as $index => $channel) {
        $row = ['channel' => $channel];
        foreach ($measures as $measure) {
            $row[$measure->name] = 10 * ($index + 1);
        }
        $rows[] = $row;
    }

    return new ChartDataset([DatasetDimension::category('channel', 'Channel')], $measures, $rows);
}

it('formats the axes of a preset by the unit of its measures', function (): void {
    app()->setLocale('pl');

    $option = new ChartPresets()->option('bar-line', formatted([
        new DatasetMeasure('revenue', 'Revenue', unit: 'PLN'),
        new DatasetMeasure('orders', 'Orders', unit: 'szt.'),
    ]))->toArray();

    expect($option['yAxis'])->toHaveCount(2)
        ->and($option['yAxis'][0]['axisLabel']['formatter']['__number__'])->toMatchArray(['style' => 'currency', 'currency' => 'PLN', 'locale' => 'pl'])
        ->and($option['yAxis'][1]['axisLabel']['formatter']['__number__'])->toMatchArray(['style' => 'decimal', 'unit' => 'szt.'])
        ->and($option['series'][0]['tooltip']['valueFormatter']['__number__']['currency'])->toBe('PLN')
        ->and($option['series'][1]['tooltip']['valueFormatter']['__number__']['unit'])->toBe('szt.');
});

it('formats a chart whose measures share a format as a whole', function (string $preset): void {
    $option = new ChartPresets()->option($preset, formatted([new DatasetMeasure('rate', 'Rate', unit: '%')]))->toArray();

    expect(json_encode($option))->toContain('"style":"percent","fraction":false');
})->with(['bar', 'line', 'pie']);

it('lets an explicit format win over the one the unit implies', function (): void {
    $option = new ChartPresets()->option('bar', formatted([
        new DatasetMeasure('rate', 'Rate', unit: '%', format: NumberFormat::percent(fraction: true)),
    ]))->toArray();

    expect($option['yAxis']['axisLabel']['formatter']['__number__']['fraction'])->toBeTrue()
        ->and($option['tooltip']['valueFormatter']['__number__']['fraction'])->toBeTrue();
});

it('leaves a chart of measures without a unit or format on the plain default', function (): void {
    $option = new ChartPresets()->option('bar', formatted([new DatasetMeasure('orders', 'Orders')]))->toArray();

    expect($option['yAxis']['axisLabel']['formatter']['__number__'])->toMatchArray(['style' => 'decimal'])
        ->and(array_keys($option['yAxis']['axisLabel']['formatter']['__number__']))->not->toContain('unit');
});

it('does not give measures that disagree one format for the whole chart', function (): void {
    $option = new ChartPresets()->option('bar', formatted([
        new DatasetMeasure('revenue', 'Revenue', unit: 'PLN'),
        new DatasetMeasure('orders', 'Orders'),
    ]))->toArray();

    expect($option['yAxis'])->toHaveCount(2)
        ->and($option['yAxis'][0]['axisLabel']['formatter']['__number__']['style'])->toBe('currency')
        ->and($option['yAxis'][1]['axisLabel']['formatter']['__number__']['style'])->toBe('decimal');
});

it('carries a measure format through the dataset array', function (): void {
    $dataset = formatted([new DatasetMeasure('revenue', 'Revenue', unit: 'PLN', format: NumberFormat::currency('PLN', 0))]);

    expect($dataset->toArray()['measures'][0]['format'])->toBe(['style' => 'currency', 'currency' => 'PLN', 'decimals' => 0])
        ->and(ChartDataset::fromArray($dataset->toArray())->measures[0]->format?->equals(NumberFormat::currency('PLN', 0)))->toBeTrue();
});

it('serialises a measure without a format exactly as before', function (): void {
    expect(new DatasetMeasure('orders', 'Orders', unit: 'pcs')->toArray())->toBe(['name' => 'orders', 'label' => 'Orders', 'unit' => 'pcs']);
});

it('refuses a malformed format in a dataset array', function (): void {
    $data = formatted([new DatasetMeasure('revenue', 'Revenue')])->toArray();
    $data['measures'][0]['format'] = ['style' => 'currency', 'currency' => 'złoty'];

    expect(fn (): ChartDataset => ChartDataset::fromArray($data))->toThrow(InvalidArgumentException::class);
});
