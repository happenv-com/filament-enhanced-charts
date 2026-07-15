<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Data\ChartData;
use Happenv\FilamentEnhancedCharts\Option\DataPoint;
use Illuminate\Support\Collection;

covers(ChartData::class);

it('splits a keyed map into labels and values (keyed pluck shape)', function () {
    $data = ChartData::fromPairs(['B2B' => 60, 'B2C' => 40]);

    expect($data->labels())->toBe(['B2B', 'B2C'])
        ->and($data->values())->toBe([60, 40]);
});

it('splits a keyed Collection into labels and values', function () {
    $data = ChartData::fromPairs(new Collection(['jan' => 5, 'feb' => 8]));

    expect($data->labels())->toBe(['jan', 'feb'])
        ->and($data->values())->toBe([5, 8]);
});

it('reads label/value columns from a time-series row list (TrendValue shape)', function () {
    // The exact shape flowframe/laravel-trend returns: objects with public
    // date/aggregate — reproduced here so the bridge is proven decoupled.
    $trend = new Collection([
        (object) ['date' => '2026-01-01', 'aggregate' => 12],
        (object) ['date' => '2026-01-02', 'aggregate' => 7],
    ]);

    $data = ChartData::fromTimeSeries($trend);

    expect($data->labels())->toBe(['2026-01-01', '2026-01-02'])
        ->and($data->values())->toBe([12, 7]);
});

it('reads custom label/value keys from array rows', function () {
    $rows = [
        ['day' => 'Mon', 'total' => 3],
        ['day' => 'Tue', 'total' => 9],
    ];

    $data = ChartData::fromTimeSeries($rows, labelKey: 'day', valueKey: 'total');

    expect($data->labels())->toBe(['Mon', 'Tue'])
        ->and($data->values())->toBe([3, 9]);
});

it('maps values to DataPoints named by their label', function () {
    $points = ChartData::fromPairs(['B2B' => 60, 'B2C' => 40])->toDataPoints();

    expect($points)->each->toBeInstanceOf(DataPoint::class)
        ->and($points[0]->toArray())->toBe(['value' => 60, 'name' => 'B2B'])
        ->and($points[1]->toArray())->toBe(['value' => 40, 'name' => 'B2C']);
});
