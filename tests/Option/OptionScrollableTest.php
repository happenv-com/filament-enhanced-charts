<?php

use Happenv\FilamentEnhancedCharts\Option\Component\DataZoom;
use Happenv\FilamentEnhancedCharts\Option\Component\Geo;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\GraphSeries;

it('leaves the option untouched by default', function () {
    $option = Option::make()->dataZoom(DataZoom::inside())->toArray();

    expect($option['dataZoom'])->not->toHaveKey('zoomOnMouseWheel');
});

it('frees the mouse wheel on inside dataZoom when scrollable', function () {
    $option = Option::make()->scrollable()->dataZoom(DataZoom::inside())->toArray();

    expect($option['dataZoom'])
        ->toMatchArray(['type' => 'inside', 'zoomOnMouseWheel' => false, 'moveOnMouseWheel' => false]);
});

it('leaves slider dataZoom untouched when scrollable (it never captures the wheel)', function () {
    $option = Option::make()->scrollable()->dataZoom(DataZoom::slider())->toArray();

    expect($option['dataZoom'])->not->toHaveKey('zoomOnMouseWheel');
});

it('downgrades series roam to move when scrollable', function () {
    $option = Option::make()->scrollable()->series(GraphSeries::make()->roam())->toArray();

    expect($option['series'][0]['roam'])->toBe('move');
});

it('downgrades geo roam to move when scrollable', function () {
    $option = Option::make()->scrollable()->geo(Geo::make()->roam())->toArray();

    expect($option['geo']['roam'])->toBe('move');
});

it('processes each entry of a multi-dataZoom list', function () {
    $option = Option::make()
        ->scrollable()
        ->dataZoom(DataZoom::slider(), DataZoom::inside())
        ->toArray();

    expect($option['dataZoom'][0])->not->toHaveKey('zoomOnMouseWheel')
        ->and($option['dataZoom'][1])->toMatchArray(['type' => 'inside', 'zoomOnMouseWheel' => false]);
});

it('can be explicitly disabled', function () {
    $option = Option::make()->scrollable(false)->dataZoom(DataZoom::inside())->toArray();

    expect($option['dataZoom'])->not->toHaveKey('zoomOnMouseWheel');
});

it('applies a host default only when scrollable() was not called explicitly', function () {
    // no explicit call → host default takes effect
    $defaulted = Option::make()->dataZoom(DataZoom::inside())->applyScrollableDefault(true)->toArray();
    expect($defaulted['dataZoom'])->toHaveKey('zoomOnMouseWheel');

    // explicit false → host default must NOT override it
    $optedOut = Option::make()->scrollable(false)->dataZoom(DataZoom::inside())->applyScrollableDefault(true)->toArray();
    expect($optedOut['dataZoom'])->not->toHaveKey('zoomOnMouseWheel');
});
