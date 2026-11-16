<?php

declare(strict_types=1);

use BcMath\Number;
use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetDimension;
use Happenv\FilamentEnhancedCharts\Data\DatasetMeasure;
use Happenv\FilamentEnhancedCharts\Enums\DimensionKind;
use Happenv\FilamentEnhancedCharts\Exceptions\InvalidChartDataset;
use Happenv\FilamentEnhancedCharts\Tests\Fixtures\ChartDatasets;

covers(ChartDataset::class);

it('round-trips through toArray() and JSON without losing anything', function (): void {
    $dataset = ChartDatasets::perMonthPerChannel();

    $restored = ChartDataset::fromArray(json_decode(json_encode($dataset->toArray(), JSON_THROW_ON_ERROR), true, flags: JSON_THROW_ON_ERROR));

    expect($restored->toArray())->toBe($dataset->toArray())
        ->and($restored->shape()->toArray())->toBe(['dimensions' => ['time', 'category'], 'measures' => 1]);
});

it('keeps null measures and null dimension values through a round trip', function (): void {
    $dataset = new ChartDataset(
        [DatasetDimension::category('channel', 'Channel')],
        [new DatasetMeasure('orders', 'Orders')],
        [['channel' => null, 'orders' => null], ['channel' => 'A', 'orders' => 1.5]],
    );

    expect(ChartDataset::fromArray($dataset->toArray())->toArray())->toBe($dataset->toArray())
        ->and($dataset->labels('channel'))->toBe([ChartDataset::NULL_LABEL, 'A']);
});

it('rejects a malformed array on restore', function (array $payload, string $message): void {
    expect(fn (): ChartDataset => ChartDataset::fromArray($payload))
        ->toThrow(InvalidChartDataset::class, $message);
})->with([
    'missing key' => [['dimensions' => [], 'measures' => []], 'missing [rows]'],
    'unknown key' => [['dimensions' => [], 'measures' => [['name' => 'a', 'label' => 'A']], 'rows' => [], 'extra' => 1], 'unknown [extra]'],
    'dimensions not a list' => [['dimensions' => ['x' => []], 'measures' => [['name' => 'a', 'label' => 'A']], 'rows' => []], 'must be a list'],
    'unknown kind' => [['dimensions' => [['name' => 'd', 'label' => 'D', 'kind' => 'geo']], 'measures' => [['name' => 'a', 'label' => 'A']], 'rows' => []], 'one of [category, time]'],
    'label not a string' => [['dimensions' => [], 'measures' => [['name' => 'a', 'label' => 5]], 'rows' => []], 'must be a string'],
    'no measure' => [['dimensions' => [], 'measures' => [], 'rows' => []], 'at least one measure'],
    'row with a missing key' => [['dimensions' => [['name' => 'd', 'label' => 'D', 'kind' => 'category']], 'measures' => [['name' => 'a', 'label' => 'A']], 'rows' => [['d' => 'x']]], 'missing [a]'],
    'row with an unknown key' => [['dimensions' => [], 'measures' => [['name' => 'a', 'label' => 'A']], 'rows' => [['a' => 1, 'b' => 2]]], 'unknown [b]'],
    'numeric string as a measure' => [['dimensions' => [], 'measures' => [['name' => 'a', 'label' => 'A']], 'rows' => [['a' => '5']]], 'finite int, float or null'],
    'non-string dimension value' => [['dimensions' => [['name' => 'd', 'label' => 'D', 'kind' => 'category']], 'measures' => [['name' => 'a', 'label' => 'A']], 'rows' => [['d' => 5, 'a' => 1]]], 'string or null'],
    'row is not an object' => [['dimensions' => [], 'measures' => [['name' => 'a', 'label' => 'A']], 'rows' => ['nope']], 'must be an object'],
]);

it('rejects invalid names and duplicate names', function (): void {
    expect(fn (): DatasetMeasure => new DatasetMeasure('1st', 'First'))->toThrow(InvalidChartDataset::class)
        ->and(fn (): ChartDataset => new ChartDataset(
            [DatasetDimension::category('x', 'X')],
            [new DatasetMeasure('x', 'X again')],
            [],
        ))->toThrow(InvalidChartDataset::class, 'used twice');
});

it('rejects non-finite measure values', function (): void {
    expect(fn (): ChartDataset => new ChartDataset([], [new DatasetMeasure('a', 'A')], [['a' => INF]]))
        ->toThrow(InvalidChartDataset::class, 'finite');
});

it('coerces query results leniently with fromRows()', function (): void {
    $dataset = ChartDataset::fromRows(
        [DatasetDimension::time('year', 'Year')],
        [new DatasetMeasure('revenue', 'Revenue'), new DatasetMeasure('orders', 'Orders')],
        [
            (object) ['year' => 2025, 'revenue' => '10.50', 'orders' => '3'],
            ['year' => '2026', 'revenue' => new Number('7'), 'orders' => null],
        ],
    );

    expect($dataset->rows)->toBe([
        ['year' => '2025', 'revenue' => 10.5, 'orders' => 3],
        ['year' => '2026', 'revenue' => 7, 'orders' => null],
    ]);
});

it('reports the shape: dimension kinds in order and the measure count', function (): void {
    $shape = ChartDatasets::perMonthTwoMeasures()->shape();

    expect($shape->dimensions)->toBe([DimensionKind::Time])
        ->and($shape->measures)->toBe(2)
        ->and($shape->describe())->toBe('1 dimension (time) and 2 measures')
        ->and(ChartDatasets::total()->shape()->describe())->toBe('no dimensions and 1 measure');
});

it('reads distinct labels in row order and sums measures per label', function (): void {
    $dataset = ChartDatasets::perMonthPerChannel();

    expect($dataset->distinct('month'))->toBe(['Jan', 'Feb', 'Mar'])
        ->and($dataset->distinct('channel'))->toBe(['Allegro', 'Amazon'])
        ->and($dataset->totals('channel', 'orders'))->toBe([
            ['name' => 'Allegro', 'value' => 18],
            ['name' => 'Amazon', 'value' => 12],
        ])
        ->and($dataset->values('orders'))->toBe([10, 4, 6, 8, 2]);
});

it('breaks a measure down into one series per second-dimension value, aligned with the first', function (): void {
    expect(ChartDatasets::perMonthPerChannel()->breakdown('month', 'channel', 'orders'))->toBe([
        ['name' => 'Allegro', 'values' => [10, null, 8]],
        ['name' => 'Amazon', 'values' => [4, 6, 2]],
    ]);
});

it('treats numeric-looking labels as strings', function (): void {
    $dataset = new ChartDataset(
        [DatasetDimension::time('year', 'Year'), DatasetDimension::category('group', 'Group')],
        [new DatasetMeasure('n', 'N')],
        [['year' => '2025', 'group' => '10', 'n' => 1], ['year' => '2026', 'group' => '10', 'n' => 2]],
    );

    expect($dataset->distinct('year'))->toBe(['2025', '2026'])
        ->and($dataset->breakdown('year', 'group', 'n'))->toBe([['name' => '10', 'values' => [1, 2]]]);
});

it('fails loudly on an unknown dimension or measure', function (): void {
    expect(fn (): array => ChartDatasets::perChannel()->labels('nope'))->toThrow(InvalidChartDataset::class)
        ->and(fn (): array => ChartDatasets::perChannel()->values('nope'))->toThrow(InvalidChartDataset::class);
});
