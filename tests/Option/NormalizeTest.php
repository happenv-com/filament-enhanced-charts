<?php

use BcMath\Number;
use Filament\Support\Colors\Color;
use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Option\DataPoint;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

it('passes scalars and null through unchanged', function () {
    expect(Normalize::value(7))->toBe(7);
    expect(Normalize::value(1.5))->toBe(1.5);
    expect(Normalize::value('Jan'))->toBe('Jan');
    expect(Normalize::value(null))->toBeNull();
});

it('turns a BcMath number into an exact literal marker', function () {
    expect(Normalize::value(new Number('19.90')))->toBe(['__js__' => '19.9']);
});

it('turns a RawJs value into a marker', function () {
    expect(Normalize::value(RawJs::make('(v)=>v')))->toBe(['__js__' => '(v)=>v']);
});

it('normalizes nested arrays including 2D and BcMath leaves', function () {
    expect(Normalize::value([1, new Number('2.50'), [3, new Number('4')]]))
        ->toBe([1, ['__js__' => '2.5'], [3, ['__js__' => '4']]]);
});

it('recurses a top-level Arrayable into its array form', function () {
    expect(Normalize::value(collect([1, new Number('2.50')])))
        ->toBe([1, ['__js__' => '2.5']]);
});

it('recurses a top-level Node into its array form', function () {
    expect(Normalize::value(DataPoint::make(new Number('5.00'))->name('A')))
        ->toBe(['value' => ['__js__' => '5'], 'name' => 'A']);
});

it('marker-izes a RawJs nested inside an array, as a formatter in series data would appear', function () {
    expect(Normalize::value(['series' => [RawJs::make('fn'), 3]]))
        ->toBe(['series' => [['__js__' => 'fn'], 3]]);
});

it('builds a js marker from a RawJs or a bare string via js()', function () {
    expect(Normalize::js(RawJs::make('(v)=>v')))->toBe(['__js__' => '(v)=>v']);
    expect(Normalize::js('(v)=>v'))->toBe(['__js__' => '(v)=>v']);
});

it('resolves a Filament color palette to its 500 shade', function () {
    expect(Normalize::color([50 => 'light', 500 => 'base', 600 => 'dark']))->toBe('base');
});

it('converts an oklch color to rgb ECharts can manipulate', function () {
    // ECharts can paint an oklch() fill but can't derive a hover/emphasis shade
    // from it — the element renders empty on hover. Convert so ECharts can.
    expect(Normalize::color('oklch(0 0 0)'))->toBe('rgb(0, 0, 0)');
    expect(Normalize::color('oklch(1 0 0)'))->toBe('rgb(255, 255, 255)');
    // A real Filament v4 palette shade (amber-500).
    expect(Normalize::color(Color::Amber))->toBe(Color::convertToRgb(Color::Amber[500]))
        ->and(Normalize::color(Color::Amber))->toStartWith('rgb(');
});

it('passes a non-oklch color through unchanged', function () {
    // Only oklch is converted; hex/rgb/keywords/gradient objects pass through
    // (Filament would otherwise mangle a keyword into `rgb(gradient)`).
    expect(Normalize::color('#22c55e'))->toBe('#22c55e');
    expect(Normalize::color('rgb(1, 2, 3)'))->toBe('rgb(1, 2, 3)');
    expect(Normalize::color('gradient'))->toBe('gradient');
    expect(Normalize::color(['type' => 'linear', 'colorStops' => []]))
        ->toBe(['type' => 'linear', 'colorStops' => []]);
});
