<?php

use Happenv\FilamentEnhancedCharts\Option\Component\Radar;
use Happenv\FilamentEnhancedCharts\Option\Series\GaugeSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\PieSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\SunburstSeries;

it('adds radius and center to a pie series', function () {
    expect(PieSeries::make()->radius(['40%', '70%'])->center(['50%', '50%'])->data([1])->toArray())
        ->toMatchArray(['type' => 'pie', 'radius' => ['40%', '70%'], 'center' => ['50%', '50%']]);
});

it('adds radius to a sunburst series', function () {
    expect(SunburstSeries::make()->radius(['0%', '90%'])->data([['name' => 'A']])->toArray())
        ->toMatchArray(['type' => 'sunburst', 'radius' => ['0%', '90%']]);
});

it('adds radius and center to a gauge series', function () {
    expect(GaugeSeries::make()->radius('80%')->center(['50%', '60%'])->data([['value' => 5]])->toArray())
        ->toMatchArray(['type' => 'gauge', 'radius' => '80%', 'center' => ['50%', '60%']]);
});

it('adds radius and center to a radar component (no ->raw needed)', function () {
    expect(Radar::make()->indicator([['name' => 'A', 'max' => 5]])->radius('70%')->center(['50%', '56%'])->toArray())
        ->toBe([
            'indicator' => [['name' => 'A', 'max' => 5]],
            'radius' => '70%',
            'center' => ['50%', '56%'],
        ]);
});

it('accepts an int radius and omits unset center', function () {
    $series = PieSeries::make()->radius(120)->data([1])->toArray();

    expect($series)->toHaveKey('radius', 120)
        ->and($series)->not->toHaveKey('center');
});

it('lets ->raw() override a radius edge', function () {
    expect(PieSeries::make()->radius('50%')->raw(['radius' => '80%'])->data([1])->toArray())
        ->toHaveKey('radius', '80%');
});
