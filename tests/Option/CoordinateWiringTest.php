<?php

use Happenv\FilamentEnhancedCharts\Option\Axis\CategoryAxis;
use Happenv\FilamentEnhancedCharts\Option\Component\AngleAxis;
use Happenv\FilamentEnhancedCharts\Option\Component\Calendar;
use Happenv\FilamentEnhancedCharts\Option\Component\Dataset;
use Happenv\FilamentEnhancedCharts\Option\Component\Geo;
use Happenv\FilamentEnhancedCharts\Option\Component\Graphic;
use Happenv\FilamentEnhancedCharts\Option\Component\Grid;
use Happenv\FilamentEnhancedCharts\Option\Component\Matrix;
use Happenv\FilamentEnhancedCharts\Option\Component\Polar;
use Happenv\FilamentEnhancedCharts\Option\Component\RadiusAxis;
use Happenv\FilamentEnhancedCharts\Option\Component\SingleAxis;
use Happenv\FilamentEnhancedCharts\Option\Component\Title;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\BarSeries;

covers(Option::class);

it('emits a single coordinate component as an object and multiple as a list', function () {
    $one = Option::make()->polar(Polar::make()->radius('75%'))->toArray();
    expect($one['polar'])->toEqual(['radius' => '75%']);

    $many = Option::make()->polar(Polar::make(), Polar::make())->toArray();
    expect($many['polar'])->toBeArray()->toHaveCount(2)
        ->and($many['polar'][0])->toEqual([]);
});

it('wires every new coordinate system + graphic into the option tree', function () {
    $option = Option::make()
        ->polar(Polar::make())
        ->angleAxis(AngleAxis::make())
        ->radiusAxis(RadiusAxis::make())
        ->singleAxis(SingleAxis::make())
        ->calendar(Calendar::make()->range('2017'))
        ->geo(Geo::make()->map('world'))
        ->matrix(Matrix::make())
        ->graphic(Graphic::make())
        ->toArray();

    expect($option)->toHaveKeys(['polar', 'angleAxis', 'radiusAxis', 'singleAxis', 'calendar', 'geo', 'matrix', 'graphic'])
        ->and($option['calendar'])->toEqual(['range' => '2017'])
        ->and($option['geo'])->toEqual(['map' => 'world']);
});

it('emits a title (single object, multiple as a list) and multi-grid with gridIndex', function () {
    $one = Option::make()->title(Title::make('Sprzedaż')->subtext('2024'))->toArray();
    expect($one['title'])->toEqual(['text' => 'Sprzedaż', 'subtext' => '2024']);

    $grids = Option::make()
        ->grid(Grid::make(), Grid::make())
        ->xAxis(CategoryAxis::make()->gridIndex(1))
        ->toArray();
    expect($grids['grid'])->toBeArray()->toHaveCount(2)
        ->and($grids['xAxis']['gridIndex'])->toBe(1);
});

it('always emits dataset as a list, even for a single dataset', function () {
    $option = Option::make()->dataset(Dataset::make()->source([[1, 2], [3, 4]]))->toArray();

    expect($option['dataset'])->toEqual([['source' => [[1, 2], [3, 4]]]]);
});

it('lets a series bind to a coordinate system and a matrix cell', function () {
    $series = BarSeries::make()
        ->coordinateSystem('polar')
        ->polarIndex(0)
        ->data([1, 2, 3])
        ->toArray();

    expect($series['coordinateSystem'])->toBe('polar')
        ->and($series['polarIndex'])->toBe(0);

    $cell = BarSeries::make()->coordinateSystem('matrix')->coord([0, 2])->data([1])->toArray();
    expect($cell['coordinateSystem'])->toBe('matrix')
        ->and($cell['coord'])->toBe([0, 2]);
});
