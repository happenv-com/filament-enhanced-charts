<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetDimension;
use Happenv\FilamentEnhancedCharts\Data\DatasetMeasure;
use Happenv\FilamentEnhancedCharts\Presets\ChartPreset;
use Happenv\FilamentEnhancedCharts\Presets\ChartPresets;
use Happenv\FilamentEnhancedCharts\Presets\Support\FlowPreset;
use Happenv\FilamentEnhancedCharts\Presets\Support\PointsPreset;
use Happenv\FilamentEnhancedCharts\Support\Countries;
use Happenv\FilamentEnhancedCharts\Tests\Fixtures\ChartDatasets as D;

covers(FlowPreset::class, PointsPreset::class);

/** @return array<string, mixed> */
function remainingOption(string $preset, ChartDataset $dataset): array
{
    return new ChartPresets()->option($preset, $dataset)->toArray();
}

/** @return list<string> */
function remainingAccepting(ChartDataset $dataset): array
{
    return array_map(
        static fn (ChartPreset $preset): string => $preset->name(),
        new ChartPresets()->accepting($dataset->shape()),
    );
}

function remainingNetwork(): ChartDataset
{
    return new ChartDataset(
        [DatasetDimension::category('from', 'From'), DatasetDimension::category('to', 'To')],
        [new DatasetMeasure('n', 'N')],
        [
            ['from' => 'A', 'to' => 'B', 'n' => 3],
            ['from' => 'A', 'to' => 'B', 'n' => 2],
            ['from' => 'B', 'to' => 'A', 'n' => 1],
            ['from' => 'A', 'to' => 'A', 'n' => 9],
            ['from' => 'B', 'to' => 'C', 'n' => 0],
            ['from' => 'C', 'to' => 'A', 'n' => null],
            ['from' => 'C', 'to' => 'A', 'n' => -4],
        ],
    );
}

it('draws a graph: summed links, nodes sized by their flow, no self-loops or empty flows', function (): void {
    $series = remainingOption('graph', remainingNetwork())['series'][0];

    expect($series['type'])->toBe('graph')
        ->and($series['layout'])->toBe('force')
        ->and($series['links'])->toHaveCount(2)
        ->and($series['links'][0])->toMatchArray(['source' => 'A', 'target' => 'B', 'value' => 5])
        ->and($series['links'][1])->toMatchArray(['source' => 'B', 'target' => 'A', 'value' => 1])
        ->and($series['links'][0]['lineStyle']['width'])->toBeGreaterThan($series['links'][1]['lineStyle']['width'])
        ->and(array_column($series['data'], 'name'))->toBe(['A', 'B'])
        ->and(array_column($series['data'], 'value'))->toBe([6, 6])
        ->and($series['data'][0]['symbolSize'])->toBe(56.0)
        ->and($series['edgeSymbol'])->toBe(['none', 'arrow']);
});

it('sizes graph nodes in proportion to what they carry', function (): void {
    $nodes = remainingOption('graph', D::channelToCountry())['series'][0]['data'];
    $sizes = array_column($nodes, 'symbolSize', 'name');

    expect(array_column($nodes, 'value', 'name'))->toBe(['Allegro' => 13, 'PL' => 12, 'CZ' => 3, 'Amazon' => 9, 'DE' => 7])
        ->and($sizes['Allegro'])->toBeGreaterThan($sizes['Amazon'])
        ->and($sizes['Amazon'])->toBeGreaterThan($sizes['CZ'])
        ->and($sizes['CZ'])->toBeGreaterThanOrEqual(14);
});

it('draws a chord from the same flows, summing repeats and dropping what cannot be drawn', function (): void {
    $series = remainingOption('chord', remainingNetwork())['series'][0];

    expect($series['type'])->toBe('chord')
        ->and($series['links'])->toBe([
            ['source' => 'A', 'target' => 'B', 'value' => 5],
            ['source' => 'B', 'target' => 'A', 'value' => 1],
        ])
        ->and(array_column($series['data'], 'name'))->toBe(['A', 'B']);
});

it('draws an empty network without failing', function (string $name): void {
    $empty = new ChartDataset(
        [DatasetDimension::category('a', 'A'), DatasetDimension::category('b', 'B')],
        [new DatasetMeasure('n', 'N')],
        [],
    );

    expect(remainingOption($name, $empty)['series'])->not->toBeEmpty();
})->with(['graph', 'chord', 'sankey']);

it('builds a tree under a root labelled by the measure, parents summing their leaves', function (): void {
    $root = remainingOption('tree', D::channelToCountry())['series'][0]['data'];

    expect($root)->toHaveCount(1)
        ->and($root[0])->toMatchArray(['name' => 'Orders', 'value' => 22])
        ->and($root[0]['children'])->toBe([
            ['name' => 'Allegro', 'value' => 13, 'children' => [['name' => 'PL', 'value' => 10], ['name' => 'CZ', 'value' => 3]]],
            ['name' => 'Amazon', 'value' => 9, 'children' => [['name' => 'DE', 'value' => 7], ['name' => 'PL', 'value' => 2]]],
        ]);
});

it('builds a tree from one to three dimensions and skips a missing value', function (): void {
    $flat = remainingOption('tree', D::perChannel())['series'][0]['data'][0];
    $three = new ChartDataset(
        [DatasetDimension::category('a', 'A'), DatasetDimension::category('b', 'B'), DatasetDimension::category('c', 'C')],
        [new DatasetMeasure('n', 'Total')],
        [['a' => 'x', 'b' => 'y', 'c' => 'z', 'n' => 4], ['a' => 'x', 'b' => 'y', 'c' => 'z', 'n' => 1], ['a' => 'x', 'b' => 'y', 'c' => 'w', 'n' => null]],
    );
    $deep = remainingOption('tree', $three)['series'][0]['data'][0];

    expect($flat['children'])->toBe([['name' => 'Allegro', 'value' => 12], ['name' => 'Amazon', 'value' => 8], ['name' => 'eBay', 'value' => 5]])
        ->and($deep)->toBe(['name' => 'Total', 'value' => 5, 'children' => [
            ['name' => 'x', 'value' => 5, 'children' => [['name' => 'y', 'value' => 5, 'children' => [['name' => 'z', 'value' => 5]]]]],
        ]])
        ->and(remainingAccepting(D::channelToCountry()))->toContain('tree');
});

it('refuses a tree of four dimensions', function (): void {
    $four = new ChartDataset(
        array_map(static fn (string $name): DatasetDimension => DatasetDimension::category($name, $name), ['a', 'b', 'c', 'd']),
        [new DatasetMeasure('n', 'N')],
        [],
    );

    expect(remainingAccepting($four))->not->toContain('tree');
});

it('draws a parallel chart with one axis per measure and one line per row', function (): void {
    $option = remainingOption('parallel', D::perChannelThreeMeasures());

    expect(array_column($option['parallelAxis'], 'name'))->toBe(['Orders', 'Returns', 'Claims'])
        ->and(array_column($option['parallelAxis'], 'dim'))->toBe([0, 1, 2])
        ->and($option['series'][0]['type'])->toBe('parallel')
        ->and($option['series'][0]['data'])->toBe([
            ['name' => 'Allegro', 'value' => [40, 4, 2]],
            ['name' => 'Amazon', 'value' => [30, 6, 1]],
        ]);
});

it('leaves a parallel line out when one of its values is missing', function (): void {
    $dataset = new ChartDataset(
        [DatasetDimension::category('c', 'C')],
        [new DatasetMeasure('a', 'A'), new DatasetMeasure('b', 'B'), new DatasetMeasure('d', 'D')],
        [['c' => 'x', 'a' => 1, 'b' => 2, 'd' => 3], ['c' => 'y', 'a' => 1, 'b' => null, 'd' => 3]],
    );

    expect(array_column(remainingOption('parallel', $dataset)['series'][0]['data'], 'name'))->toBe(['x']);
});

it('repeats a built-in symbol for pictorial bars, with no external image', function (): void {
    $option = remainingOption('pictorial-bar', D::perChannel());

    expect($option['xAxis']['data'])->toBe(['Allegro', 'Amazon', 'eBay'])
        ->and($option['series'][0])->toMatchArray(['type' => 'pictorialBar', 'symbol' => 'roundRect', 'symbolRepeat' => true, 'symbolClip' => true, 'data' => [12, 8, 5]])
        ->and(json_encode($option, JSON_THROW_ON_ERROR))->not->toContain('image://');
});

it('ripples every point of an effect scatter like a scatter plots them', function (): void {
    $effect = remainingOption('effect-scatter', D::perMonthTwoMeasures());
    $plain = remainingOption('scatter', D::perMonthTwoMeasures());

    expect(array_column($effect['series'], 'type'))->toBe(['effectScatter', 'effectScatter'])
        ->and(array_column($effect['series'], 'data'))->toBe(array_column($plain['series'], 'data'))
        ->and(array_column($effect['series'], 'name'))->toBe(array_column($plain['series'], 'name'))
        ->and($effect['xAxis'])->toBe($plain['xAxis']);
});

it('orders a boxplot by role whatever the order of the measures, and leaves an incomplete box out', function (): void {
    $option = remainingOption('boxplot', D::boxplot());

    expect($option['xAxis']['data'])->toBe(['Allegro', 'eBay'])
        ->and($option['series'][0]['type'])->toBe('boxplot')
        ->and($option['series'][0]['data'])->toBe([[1, 3, 5, 7, 9], [2, 4, 6.5, 8, 12]]);
});

it('draws a candlestick as open, close, low, high, green up and red down', function (): void {
    $option = remainingOption('candlestick', D::candles());

    expect($option['xAxis']['data'])->toBe(['Mon', 'Wed'])
        ->and($option['series'][0]['type'])->toBe('candlestick')
        ->and($option['series'][0]['data'])->toBe([[10, 11, 9, 12], [11, 8, 7, 12]])
        ->and($option['series'][0]['itemStyle'])->toMatchArray(['color' => '#16a34a', 'color0' => '#dc2626']);
});

it('needs the roles, not just the number of measures, for boxplot and candlestick', function (): void {
    $unlabelled = new ChartDataset(
        [DatasetDimension::time('day', 'Day')],
        array_map(static fn (string $name): DatasetMeasure => new DatasetMeasure($name, $name), ['a', 'b', 'c', 'd']),
        [],
    );

    expect(remainingAccepting($unlabelled))->not->toContain('candlestick')->toContain('line', 'bar-line')
        ->and(remainingAccepting(D::candles()))->toContain('candlestick', 'line', 'bar', 'bar-line', 'radar', 'parallel')
        ->and(remainingAccepting(D::candles()))->not->toContain('boxplot')
        ->and(remainingAccepting(D::boxplot()))->toContain('boxplot', 'line', 'parallel')
        ->and(remainingAccepting(D::boxplot()))->not->toContain('candlestick');
});

it('needs a time dimension for a candlestick', function (): void {
    $categorised = new ChartDataset(
        [DatasetDimension::category('day', 'Day')],
        D::candles()->measures,
        [],
    );

    expect(remainingAccepting($categorised))->not->toContain('candlestick');
});

it('draws every region of a world map by its localised name, with a visual map', function (): void {
    $option = remainingOption('world-map', D::perCountry());
    $series = $option['series'][0];

    expect($series)->toMatchArray(['type' => 'map', 'map' => 'filament-enhanced-charts-world', 'nameProperty' => 'iso', 'name' => 'Orders'])
        ->and($series['data'])->toBe([
            ['name' => 'Poland', 'value' => 150],
            ['name' => 'Germany', 'value' => 80],
            ['name' => 'Czechia', 'value' => 45],
        ])
        ->and($series['nameMap']['PL'])->toBe('Poland')
        ->and($series['nameMap'])->toHaveCount(count(Countries::codes()))
        ->and($option['visualMap'])->toMatchArray(['type' => 'continuous', 'min' => 45, 'max' => 150]);
});

it('names the countries in the app locale', function (): void {
    app()->setLocale('de');

    expect(remainingOption('world-map', D::perCountry())['series'][0]['data'][0]['name'])->toBe('Polen');
});

it('keeps the world map visual map valid with no data', function (): void {
    $empty = new ChartDataset([DatasetDimension::region('c', 'C')], [new DatasetMeasure('n', 'N')], []);

    expect(remainingOption('world-map', $empty)['visualMap'])->toMatchArray(['min' => 0, 'max' => 1]);
});

it('draws flows between countries as lines on the geo, skipping unknown codes, self-loops and empty flows', function (): void {
    $option = remainingOption('flow-lines', D::countryFlows());
    [$ends, $lines] = $option['series'];

    expect($option['geo'])->toMatchArray(['map' => 'filament-enhanced-charts-world', 'nameProperty' => 'iso'])
        ->and($ends['type'])->toBe('scatter')
        ->and($lines['type'])->toBe('lines')
        ->and($lines['coordinateSystem'])->toBe('geo')
        ->and(array_column($lines['data'], 'name'))->toBe(['Poland → Germany', 'Germany → France'])
        ->and(array_column($lines['data'], 'value'))->toBe([15, 4])
        ->and($lines['data'][0]['coords'])->toBe([Countries::centroid('PL'), Countries::centroid('DE')])
        ->and(array_column($ends['data'], 'name'))->toBe(['Poland', 'Germany', 'France'])
        ->and($ends['data'][1]['value'])->toBe([...Countries::centroid('DE') ?? [], 19]);
});

it('takes regions where a category is taken, but only the geo presets need regions', function (): void {
    $names = remainingAccepting(D::perCountry());

    expect($names)->toContain('pie', 'bar', 'treemap', 'world-map', 'pictorial-bar')
        ->and($names)->not->toContain('calendar-heatmap')
        ->and(remainingAccepting(D::perChannel()))->not->toContain('world-map')
        ->and(remainingAccepting(D::countryFlows()))->toContain('flow-lines', 'sankey', 'graph', 'chord', 'heatmap')
        ->and(remainingAccepting(D::channelToCountry()))->not->toContain('flow-lines');
});

it('accepts a region as the category of a theme river', function (): void {
    $dataset = new ChartDataset(
        [DatasetDimension::time('month', 'Month'), DatasetDimension::region('country', 'Country')],
        [new DatasetMeasure('n', 'N')],
        [['month' => 'Jan', 'country' => 'PL', 'n' => 1]],
    );

    expect(remainingAccepting($dataset))->toContain('theme-river');
});
