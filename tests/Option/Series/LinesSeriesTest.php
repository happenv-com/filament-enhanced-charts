<?php

use Happenv\FilamentEnhancedCharts\Enums\CoordinateSystem;
use Happenv\FilamentEnhancedCharts\Enums\Symbol;
use Happenv\FilamentEnhancedCharts\Option\Series\LinesSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\LinesEffect;
use Happenv\FilamentEnhancedCharts\Option\Style\LineStyle;

covers(LinesSeries::class);

it('builds a minimal lines series bound to geo via a CoordinateSystem enum', function () {
    expect(
        LinesSeries::make()
            ->coordinateSystem(CoordinateSystem::Geo)
            ->geoIndex(0)
            ->data([[[116.4, 39.9], [121.4, 31.2]]])
            ->toArray()
    )->toEqual([
        'type' => 'lines',
        'data' => [['coords' => [[116.4, 39.9], [121.4, 31.2]]]],
        'coordinateSystem' => 'geo',
        'geoIndex' => 0,
    ]);
});

it('accepts a coordinateSystem string alongside polarIndex', function () {
    expect(
        LinesSeries::make()->coordinateSystem('polar')->polarIndex(1)->data([])->toArray()
    )->toEqual([
        'type' => 'lines',
        'data' => [],
        'coordinateSystem' => 'polar',
        'polarIndex' => 1,
    ]);
});

it('passes an already-shaped data item through unchanged', function () {
    expect(
        LinesSeries::make()
            ->data([['coords' => [[0, 0], [1, 1]], 'name' => 'Flight 1']])
            ->toArray()['data']
    )->toEqual([['coords' => [[0, 0], [1, 1]], 'name' => 'Flight 1']]);
});

it('enables polyline', function () {
    expect(LinesSeries::make()->polyline()->data([])->toArray())
        ->toBe(['type' => 'lines', 'data' => [], 'polyline' => true]);
});

it('applies large and largeThreshold', function () {
    expect(LinesSeries::make()->large()->largeThreshold(2000)->data([])->toArray())
        ->toBe(['type' => 'lines', 'large' => true, 'largeThreshold' => 2000, 'data' => []]);
});

it('applies a lineStyle builder', function () {
    expect(
        LinesSeries::make()->lineStyle(LineStyle::make()->color('#f00')->curveness(0.3))->data([])->toArray()['lineStyle']
    )->toEqual(['color' => '#f00', 'curveness' => 0.3]);
});

it('applies a lineStyle array', function () {
    expect(LinesSeries::make()->lineStyle(['width' => 2])->data([])->toArray()['lineStyle'])
        ->toBe(['width' => 2]);
});

it('composes an effect builder with a Symbol enum', function () {
    expect(
        LinesSeries::make()
            ->effect(
                LinesEffect::make()
                    ->show()
                    ->period(4)
                    ->trailLength(0.3)
                    ->symbol(Symbol::Arrow)
                    ->symbolSize(8)
                    ->color('#fff')
                    ->constantSpeed(60)
            )
            ->data([])
            ->toArray()['effect']
    )->toEqual([
        'show' => true,
        'period' => 4,
        'trailLength' => 0.3,
        'symbol' => 'arrow',
        'symbolSize' => 8,
        'color' => '#fff',
        'constantSpeed' => 60,
    ]);
});

it('accepts a bare symbol string on the effect builder', function () {
    expect(LinesEffect::make()->symbol('circle')->toArray())->toEqual(['symbol' => 'circle']);
});

it('lets raw() override a typed key on the lines series', function () {
    expect(LinesSeries::make()->polyline()->raw(['polyline' => false, 'z' => 3])->data([])->toArray())
        ->toBe(['type' => 'lines', 'data' => [], 'polyline' => false, 'z' => 3]);
});
