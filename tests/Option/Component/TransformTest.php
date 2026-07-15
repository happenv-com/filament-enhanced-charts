<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Option\Component\Transform;

covers(Transform::class);

it('builds a filter transform via the static convenience', function () {
    expect(Transform::filter(['dimension' => 'Year', 'gte' => 2011])->toArray())->toEqual([
        'type' => 'filter',
        'config' => ['dimension' => 'Year', 'gte' => 2011],
    ]);
});

it('builds a sort transform via the static convenience', function () {
    expect(Transform::sort(['dimension' => 'value', 'order' => 'desc'])->toArray())->toEqual([
        'type' => 'sort',
        'config' => ['dimension' => 'value', 'order' => 'desc'],
    ]);
});

it('builds a sort transform with a list of order expressions', function () {
    expect(
        Transform::sort([
            ['dimension' => 1, 'order' => 'asc'],
            ['dimension' => 'age', 'order' => 'desc'],
        ])->toArray()
    )->toEqual([
        'type' => 'sort',
        'config' => [
            ['dimension' => 1, 'order' => 'asc'],
            ['dimension' => 'age', 'order' => 'desc'],
        ],
    ]);
});

it('builds a generic transform via type() and config()', function () {
    expect(Transform::make()->type('ecStat:regression')->config(['method' => 'linear'])->toArray())->toEqual([
        'type' => 'ecStat:regression',
        'config' => ['method' => 'linear'],
    ]);
});

it('emits print for debugging', function () {
    expect(Transform::filter(['dimension' => 'Year', 'gte' => 2011])->print()->toArray())->toEqual([
        'type' => 'filter',
        'config' => ['dimension' => 'Year', 'gte' => 2011],
        'print' => true,
    ]);
});

it('lets raw() override any typed key on transform', function () {
    expect(Transform::filter(['dimension' => 'Year', 'gte' => 2011])->raw(['type' => 'sort'])->toArray())
        ->toEqual([
            'type' => 'sort',
            'config' => ['dimension' => 'Year', 'gte' => 2011],
        ]);
});
