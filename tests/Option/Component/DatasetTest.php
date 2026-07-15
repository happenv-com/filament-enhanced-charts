<?php

declare(strict_types=1);

use BcMath\Number;
use Happenv\FilamentEnhancedCharts\Option\Component\Dataset;
use Happenv\FilamentEnhancedCharts\Option\Component\Transform;
use Illuminate\Support\Collection;

covers(Dataset::class);

it('accepts an iterable Collection of rows as the source', function () {
    $rows = new Collection([
        ['product' => 'A', 'sales' => 10],
        ['product' => 'B', 'sales' => 20],
    ]);

    expect(Dataset::make()->source($rows)->toArray()['source'])
        ->toBe([
            ['product' => 'A', 'sales' => 10],
            ['product' => 'B', 'sales' => 20],
        ]);
});

it('projects rows to the chart columns via fromModels()', function () {
    // Objects stand in for models; data_get() resolves properties, array keys
    // and dotted paths alike, keeping only the requested columns.
    $rows = new Collection([
        (object) ['id' => 1, 'date' => '2026-01-01', 'total' => 100, 'secret' => 'x'],
        (object) ['id' => 2, 'date' => '2026-01-02', 'total' => 250, 'secret' => 'y'],
    ]);

    expect(Dataset::fromModels($rows, ['date', 'total'])->toArray())
        ->toBe([
            'source' => [
                ['date' => '2026-01-01', 'total' => 100],
                ['date' => '2026-01-02', 'total' => 250],
            ],
            'dimensions' => ['date', 'total'],
        ]);
});

it('builds a dataset from a 2D array source', function () {
    expect(
        Dataset::make()->source([
            ['Product', 'Sales'],
            ['Cookies', 321],
        ])->toArray()
    )->toEqual([
        'source' => [
            ['Product', 'Sales'],
            ['Cookies', 321],
        ],
    ]);
});

it('builds a dataset from an array-of-objects source', function () {
    expect(
        Dataset::make()->source([
            ['Product' => 'Cookies', 'Sales' => 321],
            ['Product' => 'Milk Tea', 'Sales' => 261],
        ])->toArray()
    )->toEqual([
        'source' => [
            ['Product' => 'Cookies', 'Sales' => 321],
            ['Product' => 'Milk Tea', 'Sales' => 261],
        ],
    ]);
});

it('normalizes a BcMath Number inside source into a js marker', function () {
    expect(
        Dataset::make()->source([
            ['Product', 'Sales'],
            ['Cookies', new Number('321.50')],
        ])->toArray()
    )->toEqual([
        'source' => [
            ['Product', 'Sales'],
            ['Cookies', ['__js__' => '321.5']],
        ],
    ]);
});

it('sets dimensions', function () {
    expect(Dataset::make()->dimensions(['Product', 'Sales'])->toArray())->toEqual([
        'dimensions' => ['Product', 'Sales'],
    ]);
});

it('sets sourceHeader', function () {
    expect(Dataset::make()->sourceHeader()->toArray())->toEqual([
        'sourceHeader' => true,
    ]);

    expect(Dataset::make()->sourceHeader(false)->toArray())->toEqual([
        'sourceHeader' => false,
    ]);
});

it('references another dataset by index', function () {
    expect(Dataset::make()->fromDatasetIndex(0)->toArray())->toEqual([
        'fromDatasetIndex' => 0,
    ]);
});

it('references another dataset by id', function () {
    expect(Dataset::make()->fromDatasetId('raw')->toArray())->toEqual([
        'fromDatasetId' => 'raw',
    ]);
});

it('names a dataset via id', function () {
    expect(Dataset::make()->id('regressionData')->toArray())->toEqual([
        'id' => 'regressionData',
    ]);
});

it('attaches a transform built via the Transform builder', function () {
    expect(
        Dataset::make()
            ->fromDatasetIndex(0)
            ->transform(Transform::filter(['dimension' => 'Year', 'gte' => 2011]))
            ->toArray()
    )->toEqual([
        'fromDatasetIndex' => 0,
        'transform' => [
            'type' => 'filter',
            'config' => ['dimension' => 'Year', 'gte' => 2011],
        ],
    ]);
});

it('attaches a transform passed as a raw array', function () {
    expect(
        Dataset::make()
            ->transform(['type' => 'sort', 'config' => ['dimension' => 'value', 'order' => 'desc']])
            ->toArray()
    )->toEqual([
        'transform' => [
            'type' => 'sort',
            'config' => ['dimension' => 'value', 'order' => 'desc'],
        ],
    ]);
});

it('picks a transform result set by index via fromTransformResult', function () {
    expect(
        Dataset::make()
            ->fromDatasetIndex(0)
            ->transform(Transform::sort(['dimension' => 'value', 'order' => 'desc']))
            ->fromTransformResult(1)
            ->toArray()
    )->toEqual([
        'fromDatasetIndex' => 0,
        'transform' => [
            'type' => 'sort',
            'config' => ['dimension' => 'value', 'order' => 'desc'],
        ],
        'fromTransformResult' => 1,
    ]);
});

it('lets raw() override any typed key on dataset', function () {
    expect(
        Dataset::make()
            ->source([['Product', 'Sales'], ['Cookies', 321]])
            ->raw(['source' => [['Product', 'Sales'], ['Milk Tea', 261]]])
            ->toArray()
    )->toEqual([
        'source' => [
            ['Product', 'Sales'],
            ['Milk Tea', 261],
        ],
    ]);
});
