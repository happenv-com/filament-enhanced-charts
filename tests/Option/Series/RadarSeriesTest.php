<?php

use Happenv\FilamentEnhancedCharts\Option\Series\RadarSeries;

covers(RadarSeries::class);

it('binds a series to a specific radar component by index', function () {
    expect(
        RadarSeries::make()->radarIndex(1)->data([['value' => [10, 20, 30], 'name' => 'A']])->toArray()
    )->toEqual([
        'type' => 'radar',
        'data' => [['value' => [10, 20, 30], 'name' => 'A']],
        'radarIndex' => 1,
    ]);
});
