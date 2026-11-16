<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Presets\ChartPreset;
use Happenv\FilamentEnhancedCharts\Presets\ChartPresets;
use Happenv\FilamentEnhancedCharts\Presets\Support\CartesianPreset;
use Happenv\FilamentEnhancedCharts\Tests\Fixtures\ChartDatasets as D;

covers(CartesianPreset::class);

/** @return array<string, mixed> */
function unitOption(string $preset, ChartDataset $dataset): array
{
    return new ChartPresets()->option($preset, $dataset)->toArray();
}

it('keeps one plain value axis while the measures share a unit, or have none', function (array $units): void {
    $option = unitOption('bar', D::withUnits($units));

    expect($option['yAxis'])->toHaveKey('type')->and($option['yAxis'])->not->toHaveKey('name')
        ->and(array_column($option['series'], 'yAxisIndex'))->toBe([])
        ->and($option['grid']['left'])->toBe(8);
})->with([[[null, null]], [['PLN', 'PLN', 'PLN']], [[null]]]);

it('gives each distinct unit its own value axis, the second on the right', function (): void {
    $option = unitOption('bar', D::withUnits(['pcs', 'PLN', 'pcs']));

    expect($option['yAxis'])->toHaveCount(2)
        ->and(array_column($option['yAxis'], 'name'))->toBe(['pcs', 'PLN'])
        ->and(array_column($option['yAxis'], 'position'))->toBe(['left', 'right'])
        ->and(array_column($option['series'], 'yAxisIndex'))->toBe([0, 1, 0])
        ->and($option['xAxis']['type'])->toBe('category');
});

it('adds an axis per unit alternating sides and stepping outwards, with room made for them', function (): void {
    $option = unitOption('line', D::withUnits(['a', 'b', 'c', 'd']));

    expect($option['yAxis'])->toHaveCount(4)
        ->and(array_column($option['yAxis'], 'name'))->toBe(['a', 'b', 'c', 'd'])
        ->and(array_column($option['yAxis'], 'position'))->toBe(['left', 'right', 'left', 'right'])
        ->and(array_map(static fn (array $axis): int => $axis['offset'] ?? 0, $option['yAxis']))->toBe([0, 0, 60, 60])
        ->and(array_column($option['series'], 'yAxisIndex'))->toBe([0, 1, 2, 3])
        ->and($option['grid']['left'])->toBe(68)
        ->and($option['grid']['right'])->toBeGreaterThan(16 + 60 - 1);
});

it('numbers the axes by first appearance and a measure without a unit is a scale of its own', function (): void {
    $option = unitOption('area', D::withUnits(['PLN', null, 'PLN', 'pcs']));

    expect(array_column($option['series'], 'yAxisIndex'))->toBe([0, 1, 0, 2])
        ->and($option['yAxis'])->toHaveCount(3)
        ->and(array_column($option['yAxis'], 'name'))->toBe(['PLN', 'pcs']);
});

it('colours an axis like its series when it carries just one', function (): void {
    $option = unitOption('bar', D::withUnits(['pcs', 'PLN', 'pcs']));

    expect($option['yAxis'][1]['axisLine']['lineStyle']['color'])->toBe($option['series'][1]['color'])
        ->and($option['yAxis'][1]['axisLabel']['color'])->toBe($option['series'][1]['color'])
        ->and($option['yAxis'][0])->not->toHaveKey('axisLine')
        ->and(array_unique(array_column($option['series'], 'color')))->toHaveCount(3);
});

it('puts the extra value axes of a horizontal chart on x, below then above', function (): void {
    $option = unitOption('horizontal-bar', D::withUnits(['pcs', 'PLN']));

    expect($option['xAxis'])->toHaveCount(2)
        ->and(array_column($option['xAxis'], 'position'))->toBe(['bottom', 'top'])
        ->and(array_column($option['series'], 'xAxisIndex'))->toBe([0, 1])
        ->and($option['yAxis']['type'])->toBe('category');
});

it('draws a different unit on its own axis for every one-dimension-and-measures preset', function (string $preset): void {
    $option = unitOption($preset, D::withUnits(['pcs', 'PLN']));

    expect(array_column($option['series'], 'yAxisIndex'))->toBe([0, 1]);
})->with(['bar', 'line', 'smooth-line', 'area', 'grouped-bar', 'stacked-bar', 'multi-line', 'stacked-area']);

it('never stacks measures of different units', function (): void {
    $option = unitOption('stacked-bar', D::withUnits(['pcs', 'PLN', 'pcs', 'PLN']));

    expect(array_column($option['series'], 'stack'))->toBe(['total', 'total-1', 'total', 'total-1']);
});

it('still stacks a single unit together, and a second dimension never makes axes', function (): void {
    expect(array_column(unitOption('stacked-area', D::withUnits(['pcs', 'pcs']))['series'], 'stack'))->toBe(['total', 'total'])
        ->and(unitOption('stacked-bar', D::perMonthPerChannel())['yAxis'])->toHaveKey('type');
});

it('stacks within a unit on a horizontal chart as well', function (): void {
    $option = unitOption('stacked-horizontal-bar', D::withUnits(['pcs', 'PLN', 'pcs']));

    expect(array_column($option['series'], 'stack'))->toBe(['total', 'total-1', 'total'])
        ->and(array_column($option['series'], 'xAxisIndex'))->toBe([0, 1, 0]);
});

it('does not let the unit change which presets accept a dataset', function (): void {
    $registry = new ChartPresets;

    expect(array_map(static fn (ChartPreset $preset): string => $preset->name(), $registry->accepting(D::withUnits(['a', 'b', 'c', 'd', 'e'])->shape())))
        ->toContain('bar', 'bar-line', 'stacked-bar', 'line');
});

it('draws a combo: the first measure as bars, the rest as lines, on one axis', function (): void {
    $option = unitOption('bar-line', D::perMonthTwoMeasures());

    expect(array_column($option['series'], 'type'))->toBe(['bar', 'line'])
        ->and($option['yAxis'])->toHaveKey('type')
        ->and($option['xAxis']['data'])->toBe(['Jan 2026', 'Feb 2026', 'Mar 2026'])
        ->and($option['legend']['type'])->toBe('scroll');
});

it('draws a combo with the first unit as bars and every other unit as lines on its own axis', function (): void {
    $option = unitOption('bar-line', D::withUnits(['pcs', 'PLN', 'pcs', '%']));

    expect(array_column($option['series'], 'type'))->toBe(['bar', 'line', 'bar', 'line'])
        ->and(array_column($option['series'], 'yAxisIndex'))->toBe([0, 1, 0, 2])
        ->and(array_column($option['yAxis'], 'name'))->toBe(['pcs', 'PLN', '%'])
        ->and(array_column($option['yAxis'], 'offset'))->toBe([0, 0, 60]);
});

it('describes the combo for a chooser', function (): void {
    $preset = new ChartPresets()->get('bar-line');

    expect($preset->requirement())->toContain('first measure is drawn as bars')->toContain('rest as lines')
        ->and($preset->accepts(D::perChannel()->shape()))->toBeFalse()
        ->and($preset->accepts(D::perMonthPerChannel()->shape()))->toBeFalse();
});

it('makes room for every offset axis of a horizontal chart, as for a vertical one', function (): void {
    $option = unitOption('horizontal-bar', D::withUnits(['a', 'b', 'c', 'd']));

    expect(array_column($option['xAxis'], 'offset'))->toBe([0, 0, 60, 60])
        ->and($option['grid']['bottom'])->toBe(8 + 60)
        ->and($option['grid']['top'])->toBe(44 + 60 + 16);
});
