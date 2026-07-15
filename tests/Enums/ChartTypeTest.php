<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Data\ChartData;
use Happenv\FilamentEnhancedCharts\Enums\ChartType;

covers(ChartType::class);

$data = fn (): ChartData => ChartData::fromPairs(['A' => 3, 'B' => 5]);

it('builds a line series with values from data', function () use ($data) {
    expect(ChartType::Line->seriesFrom($data())->toArray())
        ->toBe(['type' => 'line', 'data' => [3, 5]]);
});

it('builds an area series as a line with an areaStyle', function () use ($data) {
    expect(ChartType::Area->seriesFrom($data())->toArray())
        ->toEqual(['type' => 'line', 'data' => [3, 5], 'areaStyle' => (object) []]);
});

it('builds a bar series with values from data', function () use ($data) {
    expect(ChartType::Bar->seriesFrom($data())->toArray())
        ->toBe(['type' => 'bar', 'data' => [3, 5]]);
});

it('builds a pie series with values as labelled DataPoints', function () use ($data) {
    expect(ChartType::Pie->seriesFrom($data())->toArray())
        ->toBe(['type' => 'pie', 'data' => [
            ['value' => 3, 'name' => 'A'],
            ['value' => 5, 'name' => 'B'],
        ]]);
});

it('marks every type cartesian except pie', function () {
    expect(ChartType::Line->isCartesian())->toBeTrue()
        ->and(ChartType::Area->isCartesian())->toBeTrue()
        ->and(ChartType::Bar->isCartesian())->toBeTrue()
        ->and(ChartType::Pie->isCartesian())->toBeFalse();
});
