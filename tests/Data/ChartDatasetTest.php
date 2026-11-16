<?php

declare(strict_types=1);

use BcMath\Number;
use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetDimension;
use Happenv\FilamentEnhancedCharts\Data\DatasetMeasure;
use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Enums\Additivity;
use Happenv\FilamentEnhancedCharts\Enums\DimensionKind;
use Happenv\FilamentEnhancedCharts\Enums\MeasureRole;
use Happenv\FilamentEnhancedCharts\Exceptions\InvalidChartDataset;
use Happenv\FilamentEnhancedCharts\Tests\Fixtures\ChartDatasets;

covers(ChartDataset::class);

it('round-trips through toArray() and JSON without losing anything', function (): void {
    $dataset = ChartDatasets::perMonthPerChannel();

    $restored = ChartDataset::fromArray(json_decode(json_encode($dataset->toArray(), JSON_THROW_ON_ERROR), true, flags: JSON_THROW_ON_ERROR));

    expect($restored->toArray())->toBe($dataset->toArray())
        ->and($restored->shape()->toArray())->toBe(['dimensions' => ['time', 'category'], 'measures' => 1, 'roles' => []]);
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
    'unknown kind' => [['dimensions' => [['name' => 'd', 'label' => 'D', 'kind' => 'geo']], 'measures' => [['name' => 'a', 'label' => 'A']], 'rows' => []], 'one of [category, time, region]'],
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

it('round-trips measure roles, and reads a v1.4 array that has none', function (): void {
    $dataset = ChartDatasets::boxplot();
    $restored = ChartDataset::fromArray(json_decode(json_encode($dataset->toArray(), JSON_THROW_ON_ERROR), true, flags: JSON_THROW_ON_ERROR));

    $v14 = [
        'dimensions' => [['name' => 'channel', 'label' => 'Channel', 'kind' => 'category']],
        'measures' => [['name' => 'orders', 'label' => 'Orders']],
        'rows' => [['channel' => 'A', 'orders' => 1]],
    ];

    expect($restored->toArray())->toBe($dataset->toArray())
        ->and(array_column($restored->toArray()['measures'], 'role'))->toBe(['median', 'min', 'q3', 'q1', 'max'])
        ->and($restored->measureWithRole(MeasureRole::Q3)?->name)->toBe('upper')
        ->and($restored->valuesOfRole(MeasureRole::Min))->toBe([1, 2, 2])
        ->and(ChartDataset::fromArray($v14)->measures[0]->role)->toBeNull()
        ->and(ChartDataset::fromArray($v14)->toArray())->toBe($v14)
        ->and(ChartDataset::fromArray($v14)->measureWithRole(MeasureRole::Min))->toBeNull()
        ->and(fn (): array => ChartDataset::fromArray($v14)->valuesOfRole(MeasureRole::Min))->toThrow(InvalidChartDataset::class, 'no measure carries the role [min]');
});

it('refuses an unknown role and a role carried twice', function (): void {
    $payload = [
        'dimensions' => [],
        'measures' => [['name' => 'a', 'label' => 'A', 'role' => 'average']],
        'rows' => [],
    ];

    expect(fn (): ChartDataset => ChartDataset::fromArray($payload))->toThrow(InvalidChartDataset::class, 'one of [min, q1, median, q3, max, open, close, low, high]')
        ->and(fn (): ChartDataset => new ChartDataset([], [DatasetMeasure::withRole(MeasureRole::Min, 'a', 'A'), DatasetMeasure::withRole(MeasureRole::Min, 'b', 'B')], []))
        ->toThrow(InvalidChartDataset::class, 'the role [min] is carried by more than one measure');
});

it('reports the roles in the shape, once each and in a fixed order', function (): void {
    $shape = ChartDatasets::boxplot()->shape();

    expect($shape->roles)->toBe([MeasureRole::Min, MeasureRole::Q1, MeasureRole::Median, MeasureRole::Q3, MeasureRole::Max])
        ->and($shape->hasRole(MeasureRole::Open))->toBeFalse()
        ->and($shape->hasExactlyRoles(MeasureRole::Max, MeasureRole::Min, MeasureRole::Median, MeasureRole::Q1, MeasureRole::Q3))->toBeTrue()
        ->and($shape->hasExactlyRoles(MeasureRole::Min))->toBeFalse()
        ->and($shape->describe())->toBe('1 dimension (category) and 5 measures (roles: min, q1, median, q3, max)')
        ->and($shape->toArray()['roles'])->toBe(['min', 'q1', 'median', 'q3', 'max'])
        ->and(DatasetShape::of(2, DimensionKind::Time)->withRoles(MeasureRole::Close, MeasureRole::Open, MeasureRole::Close)->roles)->toBe([MeasureRole::Open, MeasureRole::Close]);
});

it('round-trips a measure unit, and reads an array that has none', function (): void {
    $dataset = ChartDatasets::withUnits(['PLN', null, 'pcs']);
    $restored = ChartDataset::fromArray(json_decode(json_encode($dataset->toArray(), JSON_THROW_ON_ERROR), true, flags: JSON_THROW_ON_ERROR));

    expect($restored->toArray())->toBe($dataset->toArray())
        ->and(array_column($restored->toArray()['measures'], 'unit'))->toBe(['PLN', 'pcs'])
        ->and(array_map(static fn (DatasetMeasure $measure): ?string => $measure->unit, $restored->measures))->toBe(['PLN', null, 'pcs'])
        ->and(new DatasetMeasure('a', 'A')->toArray())->toBe(['name' => 'a', 'label' => 'A'])
        ->and(ChartDataset::fromArray(['dimensions' => [], 'measures' => [['name' => 'a', 'label' => 'A']], 'rows' => []])->measures[0]->unit)->toBeNull()
        ->and(fn (): ChartDataset => ChartDataset::fromArray(['dimensions' => [], 'measures' => [['name' => 'a', 'label' => 'A', 'unit' => 5]], 'rows' => []]))
        ->toThrow(InvalidChartDataset::class, 'measures[0].unit');
});

it('holds country codes in a region dimension', function (): void {
    $dataset = new ChartDataset([DatasetDimension::region('c', 'Country')], [new DatasetMeasure('n', 'N')], [['c' => 'PL', 'n' => 1], ['c' => null, 'n' => 2]]);
    $restored = ChartDataset::fromArray($dataset->toArray());

    expect($restored->dimensions[0]->kind)->toBe(DimensionKind::Region)
        ->and($restored->toArray()['dimensions'][0]['kind'])->toBe('region')
        ->and($restored->shape()->kindAt(0))->toBe(DimensionKind::Region);
});

it('refuses a region value that is not an uppercase alpha-2 code, but normalises one from a query', function (string $value): void {
    $make = fn (string $code): ChartDataset => new ChartDataset([DatasetDimension::region('c', 'Country')], [new DatasetMeasure('n', 'N')], [['c' => $code, 'n' => 1]]);

    expect(fn (): ChartDataset => $make($value))->toThrow(InvalidChartDataset::class, 'uppercase ISO 3166-1 alpha-2');
})->with(['pl', 'POL', 'Poland', '', 'P1', "PL\n"]);

it('upper-cases and trims region codes read leniently from rows', function (): void {
    $dataset = ChartDataset::fromRows(
        [DatasetDimension::region('c', 'Country')],
        [new DatasetMeasure('n', 'N')],
        [['c' => ' pl ', 'n' => 1], (object) ['c' => 'De', 'n' => 2], ['c' => null, 'n' => 3]],
    );

    expect($dataset->labels('c'))->toBe(['PL', 'DE', ChartDataset::NULL_LABEL]);
});

it('round-trips a multi-valued dimension and writes the flag only when it is set', function (): void {
    $dataset = new ChartDataset(
        [DatasetDimension::category('tag', 'Tag')->multiValued(), DatasetDimension::time('month', 'Month')],
        [new DatasetMeasure('orders', 'Orders')],
        [['tag' => 'gift', 'month' => 'Jan 2026', 'orders' => 3]],
    );

    $array = $dataset->toArray();
    $restored = ChartDataset::fromArray(json_decode(json_encode($array, JSON_THROW_ON_ERROR), true, flags: JSON_THROW_ON_ERROR));

    expect($array['dimensions'][0])->toBe(['name' => 'tag', 'label' => 'Tag', 'kind' => 'category', 'multiValued' => true])
        ->and($array['dimensions'][1])->toBe(['name' => 'month', 'label' => 'Month', 'kind' => 'time'])
        ->and($restored->toArray())->toBe($array)
        ->and($restored->dimensions[0]->multiValued)->toBeTrue()
        ->and($restored->dimensions[1]->multiValued)->toBeFalse()
        ->and($restored->shape()->multiValued)->toBe([0])
        ->and($restored->shape()->toArray()['multiValued'])->toBe([0]);
});

it('still reads a v1.5 dataset array that has no multiValued key', function (): void {
    $restored = ChartDataset::fromArray([
        'dimensions' => [['name' => 'channel', 'label' => 'Channel', 'kind' => 'category']],
        'measures' => [['name' => 'orders', 'label' => 'Orders']],
        'rows' => [['channel' => 'Allegro', 'orders' => 1]],
    ]);

    expect($restored->dimensions[0]->multiValued)->toBeFalse()
        ->and($restored->shape()->hasMultiValuedDimension())->toBeFalse()
        ->and($restored->shape()->toArray())->not->toHaveKey('multiValued');
});

it('rejects a multiValued flag that is not a boolean', function (): void {
    expect(fn (): ChartDataset => ChartDataset::fromArray([
        'dimensions' => [['name' => 'channel', 'label' => 'Channel', 'kind' => 'category', 'multiValued' => 'yes']],
        'measures' => [['name' => 'orders', 'label' => 'Orders']],
        'rows' => [],
    ]))->toThrow(InvalidChartDataset::class, 'dimensions[0].multiValued');
});

it('marks a dimension multi-valued through the constructor, the named constructors and the fluent method', function (): void {
    expect(new DatasetDimension('tag', 'Tag', multiValued: true)->multiValued)->toBeTrue()
        ->and(DatasetDimension::category('tag', 'Tag', multiValued: true)->multiValued)->toBeTrue()
        ->and(DatasetDimension::region('country', 'Country', multiValued: true)->multiValued)->toBeTrue()
        ->and(DatasetDimension::category('tag', 'Tag')->multiValued)->toBeFalse()
        ->and(DatasetDimension::category('tag', 'Tag')->multiValued()->multiValued)->toBeTrue()
        ->and(DatasetDimension::category('tag', 'Tag', true)->multiValued(false)->multiValued)->toBeFalse();
});

it('exposes multi-valued dimensions on the shape per position and in its description', function (): void {
    $shape = DatasetShape::of(1, DimensionKind::Time, DimensionKind::Category)->withMultiValued(1, 7);

    expect($shape->hasMultiValuedDimension())->toBeTrue()
        ->and($shape->isMultiValuedAt(0))->toBeFalse()
        ->and($shape->isMultiValuedAt(1))->toBeTrue()
        ->and($shape->multiValued)->toBe([1])
        ->and($shape->withRoles(MeasureRole::Median)->multiValued)->toBe([1])
        ->and($shape->describe())->toBe('2 dimensions (time, category (multi-valued)) and 1 measure')
        ->and($shape->toArray())->toBe(['dimensions' => ['time', 'category'], 'measures' => 1, 'roles' => [], 'multiValued' => [1]]);
});

it('round-trips measure additivity, writing it only when not additive, and reads an array that has none', function (): void {
    $dataset = new ChartDataset(
        [DatasetDimension::time('month', 'Month')],
        [new DatasetMeasure('orders', 'Orders'), new DatasetMeasure('margin', 'Margin %')->nonAdditive(), new DatasetMeasure('stock', 'Stock')->semiAdditive()],
        [['month' => 'Jan', 'orders' => 1, 'margin' => 12.5, 'stock' => 7]],
    );

    $restored = ChartDataset::fromArray(json_decode(json_encode($dataset->toArray(), JSON_THROW_ON_ERROR), true, flags: JSON_THROW_ON_ERROR));

    expect($restored->toArray())->toBe($dataset->toArray())
        ->and(array_map(static fn (DatasetMeasure $measure): Additivity => $measure->additivity, $restored->measures))
        ->toBe([Additivity::Additive, Additivity::NonAdditive, Additivity::SemiAdditive])
        ->and($dataset->toArray()['measures'][0])->not->toHaveKey('additivity')
        ->and($dataset->toArray()['measures'][1]['additivity'])->toBe('non-additive')
        ->and(ChartDataset::fromArray(['dimensions' => [], 'measures' => [['name' => 'a', 'label' => 'A']], 'rows' => []])->measures[0]->additivity)->toBe(Additivity::Additive)
        ->and(fn (): ChartDataset => ChartDataset::fromArray(['dimensions' => [], 'measures' => [['name' => 'a', 'label' => 'A', 'additivity' => 'sometimes']], 'rows' => []]))
        ->toThrow(InvalidChartDataset::class, 'measures[0].additivity');
});

it('reports measure additivity in the shape and describes it', function (): void {
    $dataset = new ChartDataset(
        [DatasetDimension::category('channel', 'Channel')],
        [new DatasetMeasure('orders', 'Orders'), new DatasetMeasure('margin', 'Margin %')->nonAdditive()],
        [],
    );
    $shape = $dataset->shape();

    expect($shape->additivity)->toBe([Additivity::Additive, Additivity::NonAdditive])
        ->and($shape->additivityAt(1))->toBe(Additivity::NonAdditive)
        ->and($shape->hasNonAdditiveMeasure())->toBeTrue()
        ->and($shape->describe())->toBe('1 dimension (category) and 2 measures (1 non-additive)')
        ->and($shape->toArray()['additivity'])->toBe(['additive', 'non-additive'])
        ->and(DatasetShape::of(2, DimensionKind::Category)->toArray())->not->toHaveKey('additivity')
        ->and($shape->withMultiValued(0)->additivity)->toBe($shape->additivity)
        ->and($shape->withRoles(MeasureRole::Min)->additivity)->toBe($shape->additivity);
});

it('never sums a non-additive measure into totals', function (): void {
    $rows = [['channel' => 'A', 'margin' => 10], ['channel' => 'A', 'margin' => 20], ['channel' => 'B', 'margin' => 30]];
    $additive = new ChartDataset([DatasetDimension::category('channel', 'Channel')], [new DatasetMeasure('margin', 'Margin %')], $rows);
    $ratio = new ChartDataset([DatasetDimension::category('channel', 'Channel')], [new DatasetMeasure('margin', 'Margin %')->nonAdditive()], $rows);
    $unique = new ChartDataset([DatasetDimension::category('channel', 'Channel')], [new DatasetMeasure('margin', 'Margin %')->nonAdditive()], [$rows[0], $rows[2]]);

    expect($additive->totals('channel', 'margin'))->toBe([['name' => 'A', 'value' => 30], ['name' => 'B', 'value' => 30]])
        ->and(fn (): array => $ratio->totals('channel', 'margin'))->toThrow(InvalidChartDataset::class, 'non-additive')
        ->and($unique->totals('channel', 'margin'))->toBe([['name' => 'A', 'value' => 10], ['name' => 'B', 'value' => 30]]);
});
