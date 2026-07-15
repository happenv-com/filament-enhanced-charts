<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Option\Axis\CategoryAxis;
use Happenv\FilamentEnhancedCharts\Option\Component\Dataset;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasData;
use Happenv\FilamentEnhancedCharts\Option\Series\BarSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\LineSeries;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Illuminate\Support\Collection;

covers(Normalize::class, HasData::class, Dataset::class);

it('drops keys from a keyed Collection passed to a series data()', function () {
    // Regression: a keyed pluck/groupBy result used to keep its keys and
    // serialize to a JSON object, which ECharts reads as an empty series.
    $keyed = collect(['jan' => 5, 'feb' => 8, 'mar' => 3]);

    expect(LineSeries::make()->data($keyed)->toArray()['data'])
        ->toBe([5, 8, 3]);
});

it('drops keys from a keyed Collection passed to a category axis data()', function () {
    expect(CategoryAxis::make()->data(collect(['a' => 'Jan', 'b' => 'Feb']))->toArray()['data'])
        ->toBe(['Jan', 'Feb']);
});

it('accepts a plain array in series data() unchanged', function () {
    expect(BarSeries::make()->data([10, 20, 30])->toArray()['data'])->toBe([10, 20, 30]);
});

it('accepts an iterable (Collection) of rows in Dataset::source()', function () {
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

it('Normalize::list drops keys while Normalize::iterable preserves them', function () {
    expect(Normalize::list(collect(['x' => 1, 'y' => 2])))->toBe([1, 2])
        ->and(Normalize::iterable(collect(['x' => 1, 'y' => 2])))->toBe(['x' => 1, 'y' => 2]);
});
