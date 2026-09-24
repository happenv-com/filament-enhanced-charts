<?php

declare(strict_types=1);

use BcMath\Number;
use Happenv\FilamentEnhancedCharts\Option\Axis\CategoryAxis;
use Happenv\FilamentEnhancedCharts\Option\Axis\LogAxis;
use Happenv\FilamentEnhancedCharts\Option\Axis\TimeAxis;
use Happenv\FilamentEnhancedCharts\Option\Axis\ValueAxis;
use Happenv\FilamentEnhancedCharts\Option\Component\DataZoom;
use Happenv\FilamentEnhancedCharts\Option\Component\Matrix;
use Happenv\FilamentEnhancedCharts\Option\Component\MatrixDimension;
use Happenv\FilamentEnhancedCharts\Option\Component\Radar;
use Happenv\FilamentEnhancedCharts\Option\Component\VisualMap;
use Happenv\FilamentEnhancedCharts\Option\Series\LineSeries;

it('sets dataMin and dataMax on numeric axes', function (): void {
    expect(ValueAxis::make()->dataMin(0)->dataMax(new Number('120.5'))->toArray())
        ->toMatchArray(['type' => 'value', 'dataMin' => 0])
        ->and(ValueAxis::make()->dataMax(new Number('120.5'))->toArray()['dataMax'])->toBeInstanceOf(Number::class)
        ->and(LogAxis::make()->dataMax(1000)->toArray())->toMatchArray(['type' => 'log', 'dataMax' => 1000])
        ->and(TimeAxis::make()->dataMin('2026-01-01')->toArray())->toMatchArray(['type' => 'time', 'dataMin' => '2026-01-01']);
});

it('keeps dataMin and dataMax off the category axis', function (): void {
    expect(method_exists(CategoryAxis::class, 'dataMin'))->toBeFalse()
        ->and(ValueAxis::make()->toArray())->not->toHaveKeys(['dataMin', 'dataMax']);
});

it('sets containShape on any axis', function (): void {
    expect(CategoryAxis::make()->containShape()->toArray())->toMatchArray(['containShape' => true])
        ->and(ValueAxis::make()->containShape(false)->toArray())->toMatchArray(['containShape' => false]);
});

it('sets the radar direction', function (): void {
    expect(Radar::make()->indicator([['name' => 'A', 'max' => 1]])->clockwise(false)->toArray())
        ->toMatchArray(['clockwise' => false]);
});

it('maps several series through visualMap seriesTargets', function (): void {
    $targets = [['seriesIndex' => 0, 'dimension' => 1], ['seriesId' => 'b', 'dimension' => 2]];

    expect(VisualMap::continuous()->seriesTargets($targets)->toArray()['seriesTargets'])->toBe($targets);
});

it('builds a headless matrix dimension and matrix cell events', function (): void {
    expect(MatrixDimension::make()->length(7)->toArray())->toBe(['length' => 7])
        ->and(Matrix::make()->x(MatrixDimension::make()->length(3))->triggerEvent()->toArray())
        ->toBe(['x' => ['length' => 3], 'triggerEvent' => true]);
});

it('sets the line series triggerEvent', function (): void {
    expect(LineSeries::make()->triggerEvent()->toArray())->toMatchArray(['triggerEvent' => true])
        ->and(LineSeries::make()->triggerEvent('area')->toArray())->toMatchArray(['triggerEvent' => 'area']);
});

it('sets the inside data zoom grab cursors', function (): void {
    expect(DataZoom::inside()->cursorGrab('grab')->cursorGrabbing('grabbing')->toArray())
        ->toMatchArray(['type' => 'inside', 'cursorGrab' => 'grab', 'cursorGrabbing' => 'grabbing']);
});
