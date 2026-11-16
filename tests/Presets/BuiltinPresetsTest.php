<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetDimension;
use Happenv\FilamentEnhancedCharts\Data\DatasetMeasure;
use Happenv\FilamentEnhancedCharts\Exceptions\ChartPresetException;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Presets\AbstractChartPreset;
use Happenv\FilamentEnhancedCharts\Presets\ChartPresets;
use Happenv\FilamentEnhancedCharts\Presets\Support\CartesianPreset;
use Happenv\FilamentEnhancedCharts\Presets\Support\HierarchyPreset;
use Happenv\FilamentEnhancedCharts\Presets\Support\NamedSlicesPreset;
use Happenv\FilamentEnhancedCharts\Tests\Fixtures\ChartDatasets as D;

covers(AbstractChartPreset::class, CartesianPreset::class, HierarchyPreset::class, NamedSlicesPreset::class);

/** @return array<string, mixed> */
function presetOptionArray(string $preset, ChartDataset $dataset): array
{
    return new ChartPresets()->option($preset, $dataset)->toArray();
}

/**
 * Every built-in preset: a dataset it accepts and one it must refuse.
 *
 * @return array<string, array{0: string, 1: Closure(): ChartDataset, 2: Closure(): ChartDataset}>
 */
function builtInPresetCases(): array
{
    return [
        'pie' => ['pie', D::perChannel(...), D::perMonthPerChannel(...)],
        'donut' => ['donut', D::perChannel(...), D::perMonthPerChannel(...)],
        'rose' => ['rose', D::perChannel(...), D::perMonthTwoMeasures(...)],
        'funnel' => ['funnel', D::perChannel(...), D::total(...)],
        'bar' => ['bar', D::perMonthTwoMeasures(...), D::total(...)],
        'horizontal-bar' => ['horizontal-bar', D::perChannel(...), D::perMonthPerChannel(...)],
        'line' => ['line', D::perMonthTwoMeasures(...), D::perMonthPerChannel(...)],
        'smooth-line' => ['smooth-line', D::perChannel(...), D::total(...)],
        'area' => ['area', D::perChannelThreeMeasures(...), D::total(...)],
        'grouped-bar' => ['grouped-bar', D::perMonthPerChannel(...), D::perChannel(...)],
        'stacked-bar' => ['stacked-bar', D::perMonthPerChannel(...), D::perChannel(...)],
        'stacked-horizontal-bar' => ['stacked-horizontal-bar', D::perMonthTwoMeasures(...), D::perChannel(...)],
        'multi-line' => ['multi-line', D::perMonthPerChannel(...), D::total(...)],
        'stacked-area' => ['stacked-area', D::perMonthPerChannel(...), D::perChannel(...)],
        'heatmap' => ['heatmap', D::channelToCountry(...), D::perChannel(...)],
        'sankey' => ['sankey', D::channelToCountry(...), D::perChannel(...)],
        'treemap' => ['treemap', D::channelToCountry(...), D::total(...)],
        'sunburst' => ['sunburst', D::perChannel(...), D::perChannelThreeMeasures(...)],
        'radar' => ['radar', D::perChannelThreeMeasures(...), D::perChannel(...)],
        'polar-bar' => ['polar-bar', D::perDay(...), D::perChannelThreeMeasures(...)],
        'gauge' => ['gauge', D::total(...), D::perChannel(...)],
        'theme-river' => ['theme-river', D::perMonthPerChannel(...), D::channelToCountry(...)],
        'calendar-heatmap' => ['calendar-heatmap', D::perDay(...), D::perChannel(...)],
        'scatter' => ['scatter', D::perMonthTwoMeasures(...), D::perChannel(...)],
    ];
}

dataset('built-in presets', fn (): array => builtInPresetCases());

dataset('built-in preset datasets', fn (): array => array_map(
    static fn (array $case): array => [$case[0], $case[1]],
    builtInPresetCases(),
));

it('accepts its declared shape and rejects a wrong one', function (string $name, Closure $accepted, Closure $rejected): void {
    $preset = new ChartPresets()->get($name);

    expect($preset->accepts($accepted()->shape()))->toBeTrue()
        ->and($preset->accepts($rejected()->shape()))->toBeFalse()
        ->and(fn (): Option => $preset->option($rejected()))
        ->toThrow(ChartPresetException::class, "Chart preset [{$name}] needs");
})->with('built-in presets');

it('builds an option that carries a tooltip and no raw escape hatch', function (string $name, Closure $accepted): void {
    $option = presetOptionArray($name, $accepted());

    expect($option)->toHaveKey('tooltip')
        ->and($option['series'])->not->toBeEmpty()
        ->and(json_encode($option, JSON_THROW_ON_ERROR))->not->toContain('__js__');
})->with('built-in preset datasets');

it('describes itself for a chooser', function (): void {
    foreach (ChartPresets::builtIn() as $preset) {
        expect($preset->name())->toMatch('/^[a-z]+(-[a-z]+)*$/')
            ->and($preset->description())->toEndWith('.')->and(strlen($preset->description()))->toBeGreaterThan(30)
            ->and($preset->requirement())->not->toBeEmpty();
    }
});

it('draws named slices for pie, donut, rose and funnel', function (string $name, string $type): void {
    $option = presetOptionArray($name, D::perChannel());

    expect($option['series'][0]['type'])->toBe($type)
        ->and($option['series'][0]['data'])->toBe([
            ['value' => 12, 'name' => 'Allegro'],
            ['value' => 8, 'name' => 'Amazon'],
            ['value' => 5, 'name' => 'eBay'],
        ])
        ->and($option['tooltip']['trigger'])->toBe('item')
        ->and($option['legend']['type'])->toBe('scroll');
})->with([
    ['pie', 'pie'],
    ['donut', 'pie'],
    ['rose', 'pie'],
    ['funnel', 'funnel'],
]);

it('shapes pie variants differently', function (): void {
    expect(presetOptionArray('pie', D::perChannel())['series'][0]['radius'])->toBe('62%')
        ->and(presetOptionArray('donut', D::perChannel())['series'][0]['radius'])->toBe(['42%', '66%'])
        ->and(presetOptionArray('rose', D::perChannel())['series'][0]['roseType'])->toBe('radius')
        ->and(presetOptionArray('pie', D::perChannel())['series'][0]['label'])->toBe(['show' => false]);
});

it('sums repeated slice labels and treats a missing value as zero', function (): void {
    $dataset = new ChartDataset(
        [DatasetDimension::category('channel', 'Channel')],
        [new DatasetMeasure('orders', 'Orders')],
        [['channel' => 'A', 'orders' => 1], ['channel' => 'A', 'orders' => 2], ['channel' => 'B', 'orders' => null]],
    );

    expect(presetOptionArray('pie', $dataset)['series'][0]['data'])->toBe([
        ['value' => 3, 'name' => 'A'],
        ['value' => 0, 'name' => 'B'],
    ]);
});

it('draws one series per measure on a category axis for bar, line and area', function (string $name, string $type): void {
    $option = presetOptionArray($name, D::perMonthTwoMeasures());

    expect($option['xAxis'])->toMatchArray(['type' => 'category', 'data' => ['Jan 2026', 'Feb 2026', 'Mar 2026']])
        ->and($option['yAxis']['type'])->toBe('value')
        ->and(array_column($option['series'], 'type'))->toBe([$type, $type])
        ->and(array_column($option['series'], 'name'))->toBe(['Orders', 'Revenue'])
        ->and($option['series'][0]['data'])->toBe([10, 20, 15])
        ->and($option['series'][1]['data'])->toBe([100.5, 150, null])
        ->and($option['tooltip']['trigger'])->toBe('axis')
        ->and($option['legend']['type'])->toBe('scroll');
})->with([
    ['bar', 'bar'],
    ['line', 'line'],
    ['smooth-line', 'line'],
    ['area', 'line'],
]);

it('omits the legend for a single series', function (): void {
    expect(presetOptionArray('bar', D::perChannel()))->not->toHaveKey('legend');
});

it('does not assume a time dimension is an ISO date', function (): void {
    expect(presetOptionArray('line', D::perMonthTwoMeasures())['xAxis']['type'])->toBe('category');
});

it('marks line variants', function (): void {
    expect(presetOptionArray('smooth-line', D::perChannel())['series'][0]['smooth'])->toBeTrue()
        ->and(presetOptionArray('area', D::perChannel())['series'][0])->toHaveKey('areaStyle')
        ->and(presetOptionArray('line', D::perChannel())['series'][0])->not->toHaveKey('areaStyle')
        ->and(presetOptionArray('area', D::perChannel())['xAxis']['boundaryGap'])->toBeFalse();
});

it('puts the categories on the y axis, first at the top, for horizontal bars', function (): void {
    $option = presetOptionArray('horizontal-bar', D::perChannel());

    expect($option['xAxis']['type'])->toBe('value')
        ->and($option['yAxis'])->toMatchArray(['type' => 'category', 'inverse' => true, 'data' => ['Allegro', 'Amazon', 'eBay']]);
});

it('splits the second dimension into series, aligned with the first, for the breakdown presets', function (string $name, string $type, ?string $stack): void {
    $option = presetOptionArray($name, D::perMonthPerChannel());

    expect($option['xAxis']['data'])->toBe(['Jan', 'Feb', 'Mar'])
        ->and(array_column($option['series'], 'name'))->toBe(['Allegro', 'Amazon'])
        ->and(array_column($option['series'], 'type'))->toBe([$type, $type])
        ->and($option['series'][0]['data'])->toBe([10, null, 8])
        ->and($option['series'][1]['data'])->toBe([4, 6, 2])
        ->and($option['series'][0]['stack'] ?? null)->toBe($stack);
})->with([
    ['grouped-bar', 'bar', null],
    ['stacked-bar', 'bar', 'total'],
    ['multi-line', 'line', null],
    ['stacked-area', 'line', 'total'],
]);

it('stacks horizontally with the categories on the y axis', function (): void {
    $option = presetOptionArray('stacked-horizontal-bar', D::perMonthPerChannel());

    expect($option['yAxis']['data'])->toBe(['Jan', 'Feb', 'Mar'])
        ->and($option['xAxis']['type'])->toBe('value')
        ->and($option['series'][0]['stack'])->toBe('total');
});

it('treats several measures as the series of a breakdown', function (): void {
    $option = presetOptionArray('stacked-bar', D::perMonthTwoMeasures());

    expect(array_column($option['series'], 'name'))->toBe(['Orders', 'Revenue'])
        ->and($option['series'][0]['stack'])->toBe('total');
});

it('lays a heatmap out as x index, y index, value with a visual map', function (): void {
    $option = presetOptionArray('heatmap', D::channelToCountry());

    expect($option['xAxis']['data'])->toBe(['Allegro', 'Amazon'])
        ->and($option['yAxis']['data'])->toBe(['PL', 'CZ', 'DE'])
        ->and($option['series'][0]['type'])->toBe('heatmap')
        ->and($option['series'][0]['data'])->toBe([[0, 0, 10], [0, 1, 3], [1, 2, 7], [1, 0, 2]])
        ->and($option['visualMap'])->toMatchArray(['type' => 'continuous', 'min' => 2, 'max' => 10]);
});

it('keeps a heatmap visual map valid when every cell is equal or there are no cells', function (): void {
    $empty = new ChartDataset(
        [DatasetDimension::category('a', 'A'), DatasetDimension::category('b', 'B')],
        [new DatasetMeasure('n', 'N')],
        [],
    );

    expect(presetOptionArray('heatmap', $empty)['visualMap'])->toMatchArray(['min' => 0, 'max' => 1])
        ->and(presetOptionArray('heatmap', $empty)['series'][0]['data'])->toBe([]);
});

it('draws a sankey from source to target and drops flows without a positive size', function (): void {
    $dataset = new ChartDataset(
        [DatasetDimension::category('from', 'From'), DatasetDimension::category('to', 'To')],
        [new DatasetMeasure('n', 'N')],
        [
            ['from' => 'A', 'to' => 'B', 'n' => 3],
            ['from' => 'A', 'to' => 'B', 'n' => 2],
            ['from' => 'A', 'to' => 'A', 'n' => 9],
            ['from' => 'B', 'to' => 'C', 'n' => 0],
            ['from' => 'B', 'to' => 'C', 'n' => null],
        ],
    );

    $series = presetOptionArray('sankey', $dataset)['series'][0];

    expect($series['type'])->toBe('sankey')
        ->and($series['links'])->toBe([
            ['source' => 'A', 'target' => "B\u{200B}", 'value' => 5],
            ['source' => 'A', 'target' => "A\u{200B}", 'value' => 9],
        ])
        ->and($series['data'])->toBe([['name' => 'A'], ['name' => "B\u{200B}"], ['name' => "A\u{200B}"]]);
});

it('gives a sankey node a separate identity per stage so opposite flows are not a cycle', function (): void {
    $dataset = new ChartDataset(
        [DatasetDimension::category('from', 'From'), DatasetDimension::category('to', 'To')],
        [new DatasetMeasure('n', 'N')],
        [['from' => 'A', 'to' => 'B', 'n' => 3], ['from' => 'B', 'to' => 'A', 'n' => 4]],
    );

    $series = presetOptionArray('sankey', $dataset)['series'][0];
    $names = array_column($series['data'], 'name');
    $displayed = array_map(fn (string $name): string => str_replace("\u{200B}", '', $name), $names);

    expect($names)->toHaveCount(4)
        ->and(array_unique($names))->toHaveCount(4)
        ->and($displayed)->toBe(['A', 'B', 'B', 'A'])
        ->and(array_intersect(array_column($series['links'], 'source'), array_column($series['links'], 'target')))->toBe([]);
});

it('nests the second dimension inside the first for treemap and sunburst', function (string $name): void {
    $series = presetOptionArray($name, D::channelToCountry())['series'][0];

    expect($series['type'])->toBe($name)
        ->and($series['data'])->toBe([
            ['name' => 'Allegro', 'children' => [['name' => 'PL', 'value' => 10], ['name' => 'CZ', 'value' => 3]]],
            ['name' => 'Amazon', 'children' => [['name' => 'DE', 'value' => 7], ['name' => 'PL', 'value' => 2]]],
        ]);
})->with(['treemap', 'sunburst']);

it('draws a flat hierarchy from one dimension', function (string $name): void {
    expect(presetOptionArray($name, D::perChannel())['series'][0]['data'])->toBe([
        ['name' => 'Allegro', 'value' => 12],
        ['name' => 'Amazon', 'value' => 8],
        ['name' => 'eBay', 'value' => 5],
    ]);
})->with(['treemap', 'sunburst']);

it('takes radar axes from the first dimension and entities from the second', function (): void {
    $option = presetOptionArray('radar', D::channelToCountry());

    expect(array_column($option['radar']['indicator'], 'name'))->toBe(['Allegro', 'Amazon'])
        ->and($option['series'][0]['type'])->toBe('radar')
        ->and($option['series'][0]['data'])->toBe([
            ['name' => 'PL', 'value' => [10, 2]],
            ['name' => 'CZ', 'value' => [3, 0]],
            ['name' => 'DE', 'value' => [0, 7]],
        ]);
});

it('takes radar axes from the measures and entities from the rows', function (): void {
    $option = presetOptionArray('radar', D::perChannelThreeMeasures());

    expect(array_column($option['radar']['indicator'], 'name'))->toBe(['Orders', 'Returns', 'Claims'])
        ->and(array_unique(array_column($option['radar']['indicator'], 'max')))->toBe([50])
        ->and($option['series'][0]['data'])->toBe([
            ['name' => 'Allegro', 'value' => [40, 4, 2]],
            ['name' => 'Amazon', 'value' => [30, 6, 1]],
        ])
        ->and($option['legend']['type'])->toBe('scroll');
});

it('arranges polar bars around a circle', function (): void {
    $option = presetOptionArray('polar-bar', D::perChannel());

    expect($option['angleAxis'])->toMatchArray(['type' => 'category', 'data' => ['Allegro', 'Amazon', 'eBay']])
        ->and($option)->toHaveKey('radiusAxis')
        ->and($option['series'][0])->toMatchArray(['type' => 'bar', 'coordinateSystem' => 'polar', 'data' => [12, 8, 5]]);
});

it('shows a gauge with headroom above the value', function (int | float $value, int | float $max): void {
    $dataset = new ChartDataset([], [new DatasetMeasure('n', 'Orders')], [['n' => $value]]);

    $series = presetOptionArray('gauge', $dataset)['series'][0];

    expect($series['type'])->toBe('gauge')
        ->and($series['data'])->toBe([['value' => $value, 'name' => 'Orders']])
        ->and($series['min'])->toBe(0)
        ->and($series['max'])->toEqual($max);
})->with([
    'tens' => [73, 100],
    'exact' => [50, 50],
    'hundreds' => [120, 200],
    'fraction' => [0.4, 0.5],
    'zero' => [0, 1],
    'below one' => [0.3, 0.5],
    'thousands' => [1234, 2000],
]);

it('shows a gauge of a negative value on a scale that contains it', function (int | float $value, int | float $min): void {
    $dataset = new ChartDataset([], [new DatasetMeasure('n', 'Orders')], [['n' => $value]]);

    $series = presetOptionArray('gauge', $dataset)['series'][0];

    expect($series['min'])->toEqual($min)
        ->and($series['max'])->toBe(0);
})->with([
    'tens' => [-50, -50],
    'between' => [-73, -100],
    'fraction' => [-0.3, -0.5],
    'thousands' => [-1234, -2000],
]);

it('shows a gauge of an empty dataset as zero and refuses more than one row', function (): void {
    $empty = new ChartDataset([], [new DatasetMeasure('n', 'Orders')], []);
    $many = new ChartDataset([], [new DatasetMeasure('n', 'Orders')], [['n' => 1], ['n' => 2]]);

    expect(presetOptionArray('gauge', $empty)['series'][0]['data'])->toBe([['value' => 0, 'name' => 'Orders']])
        ->and(fn (): array => presetOptionArray('gauge', $many))->toThrow(ChartPresetException::class, 'one value');
});

it('draws a theme river with every category at every period', function (): void {
    $option = presetOptionArray('theme-river', D::perMonthPerChannel());

    expect($option['singleAxis'])->toMatchArray(['type' => 'category', 'data' => ['Jan', 'Feb', 'Mar']])
        ->and($option['series'][0]['type'])->toBe('themeRiver')
        ->and($option['series'][0]['data'])->toBe([
            ['Jan', 10, 'Allegro'], ['Jan', 4, 'Amazon'],
            ['Feb', 0, 'Allegro'], ['Feb', 6, 'Amazon'],
            ['Mar', 8, 'Allegro'], ['Mar', 2, 'Amazon'],
        ]);
});

it('accepts the theme river dimensions in either order', function (): void {
    $swapped = new ChartDataset(
        [DatasetDimension::category('channel', 'Channel'), DatasetDimension::time('month', 'Month')],
        [new DatasetMeasure('n', 'N')],
        [['channel' => 'A', 'month' => 'Jan', 'n' => 1], ['channel' => 'B', 'month' => 'Jan', 'n' => 2]],
    );

    expect(presetOptionArray('theme-river', $swapped)['series'][0]['data'])->toBe([['Jan', 1, 'A'], ['Jan', 2, 'B']]);
});

it('draws a calendar heatmap over the range of days, sorted', function (): void {
    $option = presetOptionArray('calendar-heatmap', D::perDay());

    expect($option['calendar']['range'])->toBe(['2026-03-01', '2026-03-03'])
        ->and($option['series'][0])->toMatchArray(['type' => 'heatmap', 'coordinateSystem' => 'calendar', 'calendarIndex' => 0])
        ->and($option['series'][0]['data'])->toBe([['2026-03-01', 9], ['2026-03-02', 1], ['2026-03-03', 4]])
        ->and($option['visualMap'])->toMatchArray(['min' => 0, 'max' => 9]);
});

it('reads a day from a timestamp and sums rows of the same day', function (): void {
    $dataset = new ChartDataset(
        [DatasetDimension::time('day', 'Day')],
        [new DatasetMeasure('n', 'N')],
        [['day' => '2026-03-01 00:00:00', 'n' => 2], ['day' => '2026-03-01 12:00:00', 'n' => 3]],
    );

    expect(presetOptionArray('calendar-heatmap', $dataset)['series'][0]['data'])->toBe([['2026-03-01', 5]]);
});

it('refuses a calendar heatmap whose time values are not days', function (string $label): void {
    $dataset = new ChartDataset(
        [DatasetDimension::time('day', 'Day')],
        [new DatasetMeasure('n', 'N')],
        [['day' => $label, 'n' => 1]],
    );

    expect(fn (): array => presetOptionArray('calendar-heatmap', $dataset))->toThrow(ChartPresetException::class, 'YYYY-MM-DD');
})->with(['Jan 2026', '2026-02-30', '2026-03', '2026-03-011']);

it('plots one point per row, named by the dimension, with the measures as x and y', function (): void {
    $option = presetOptionArray('scatter', D::perMonthTwoMeasures());

    expect(array_column($option['series'], 'type'))->toBe(['scatter', 'scatter'])
        ->and(array_column($option['series'], 'name'))->toBe(['Jan 2026', 'Feb 2026'])
        ->and($option['series'][0]['data'])->toBe([[10, 100.5]])
        ->and($option['xAxis']['name'])->toBe('Orders')
        ->and($option['yAxis']['name'])->toBe('Revenue')
        ->and($option['legend']['type'])->toBe('scroll');
});

it('does not draw a scatter point with a missing coordinate', function (): void {
    expect(array_column(presetOptionArray('scatter', D::perMonthTwoMeasures())['series'], 'name'))->not->toContain('Mar 2026');
});

it('builds an empty dataset without failing', function (string $name): void {
    $empty = new ChartDataset([DatasetDimension::category('a', 'A')], [new DatasetMeasure('n', 'N')], []);
    $registry = new ChartPresets;

    foreach ($registry->accepting($empty->shape()) as $preset) {
        expect($preset->option($empty)->toArray())->toBeArray();
    }

    expect($registry->has($name))->toBeTrue();
})->with(['pie']);
