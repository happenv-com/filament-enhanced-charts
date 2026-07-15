<?php

declare(strict_types=1);

use BcMath\Number;
use Happenv\FilamentEnhancedCharts\Option\Series\MapSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;

covers(MapSeries::class);

it('builds a map series with a map name and data', function () {
    expect(
        MapSeries::make()
            ->map('USA')
            ->data([['name' => 'California', 'value' => 1], ['name' => 'Texas', 'value' => 2]])
            ->toArray()
    )->toEqual([
        'type' => 'map',
        'data' => [['name' => 'California', 'value' => 1], ['name' => 'Texas', 'value' => 2]],
        'map' => 'USA',
    ]);
});

it('normalizes a BcMath value in map data to a marker', function () {
    expect(
        MapSeries::make()
            ->map('world')
            ->data([['name' => 'France', 'value' => new Number('12.50')]])
            ->toArray()['data']
    )->toEqual([['name' => 'France', 'value' => ['__js__' => '12.5']]]);
});

it('accepts roam as a boolean or a gesture string', function () {
    expect(MapSeries::make()->map('world')->roam()->toArray()['roam'])->toBeTrue();
    expect(MapSeries::make()->map('world')->roam('scale')->toArray()['roam'])->toBe('scale');
});

it('applies nameProperty and selectedMode', function () {
    expect(
        MapSeries::make()->map('world')->nameProperty('NAME')->selectedMode('multiple')->toArray()
    )->toEqual([
        'type' => 'map',
        'map' => 'world',
        'nameProperty' => 'NAME',
        'selectedMode' => 'multiple',
    ]);

    expect(MapSeries::make()->map('world')->selectedMode(true)->toArray()['selectedMode'])->toBeTrue();
});

it('accepts label and itemStyle as builders or arrays', function () {
    $viaBuilder = MapSeries::make()
        ->map('world')
        ->label(Label::make()->show())
        ->itemStyle(ItemStyle::make()->color('#c23531'))
        ->toArray();

    $viaArray = MapSeries::make()
        ->map('world')
        ->label(['show' => true])
        ->itemStyle(['color' => '#c23531'])
        ->toArray();

    expect($viaBuilder)->toEqual([
        'type' => 'map',
        'map' => 'world',
        'label' => ['show' => true],
        'itemStyle' => ['color' => '#c23531'],
    ]);
    expect($viaArray)->toBe($viaBuilder);
});

it('applies zoom, center and geoIndex', function () {
    expect(
        MapSeries::make()->map('world')->zoom(1.5)->center([104.0, 37.5])->geoIndex(0)->toArray()
    )->toEqual([
        'type' => 'map',
        'map' => 'world',
        'zoom' => 1.5,
        'center' => [104.0, 37.5],
        'geoIndex' => 0,
    ]);
});

it('lets raw() override a map series map name', function () {
    expect(MapSeries::make()->map('world')->raw(['map' => 'USA'])->toArray()['map'])->toBe('USA');
});
