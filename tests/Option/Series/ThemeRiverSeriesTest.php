<?php

use BcMath\Number;
use Happenv\FilamentEnhancedCharts\Option\Series\ThemeRiverSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;

covers(ThemeRiverSeries::class);

it('builds a themeRiver series with time/value/name rows, normalizing BcMath values', function () {
    expect(
        ThemeRiverSeries::make()
            ->data([
                ['2015/11/08', new Number('10.50'), 'DQ'],
                ['2015/11/09', 15, 'DQ'],
            ])
            ->toArray()
    )->toBe([
        'type' => 'themeRiver',
        'data' => [
            ['2015/11/08', ['__js__' => '10.5'], 'DQ'],
            ['2015/11/09', 15, 'DQ'],
        ],
    ]);
});

it('binds to a singleAxis by index', function () {
    expect(ThemeRiverSeries::make()->singleAxisIndex(0)->toArray())
        ->toBe(['type' => 'themeRiver', 'singleAxisIndex' => 0]);
});

it('accepts a Label object or a plain array for label()', function () {
    $viaObject = ThemeRiverSeries::make()->label(Label::make()->show()->position('right'))->toArray();
    $viaArray = ThemeRiverSeries::make()->label(['show' => true, 'position' => 'right'])->toArray();

    expect($viaObject)->toBe(['type' => 'themeRiver', 'label' => ['show' => true, 'position' => 'right']])
        ->and($viaArray)->toBe($viaObject);
});

it('accepts an ItemStyle object or a plain array for itemStyle()', function () {
    $viaObject = ThemeRiverSeries::make()->itemStyle(ItemStyle::make()->borderColor('#fff'))->toArray();
    $viaArray = ThemeRiverSeries::make()->itemStyle(['borderColor' => '#fff'])->toArray();

    expect($viaObject)->toBe(['type' => 'themeRiver', 'itemStyle' => ['borderColor' => '#fff']])
        ->and($viaArray)->toBe($viaObject);
});

it('sets boundaryGap', function () {
    expect(ThemeRiverSeries::make()->boundaryGap(['10%', '10%'])->toArray())
        ->toBe(['type' => 'themeRiver', 'boundaryGap' => ['10%', '10%']]);
});

it('applies left/right/top/bottom layout via HasLayout', function () {
    expect(ThemeRiverSeries::make()->left('10%')->right(20)->top('5%')->bottom(10)->toArray())
        ->toBe(['type' => 'themeRiver', 'left' => '10%', 'right' => 20, 'top' => '5%', 'bottom' => 10]);
});

it('inherits name, color and emphasis from the base series without redeclaring them', function () {
    expect(
        ThemeRiverSeries::make()
            ->name('Themes')
            ->color('#6366f1')
            ->emphasis(['focus' => 'series'])
            ->toArray()
    )->toBe([
        'type' => 'themeRiver',
        'name' => 'Themes',
        'color' => '#6366f1',
        'emphasis' => ['focus' => 'series'],
    ]);
});

it('lets raw() override a themeRiver series type', function () {
    expect(ThemeRiverSeries::make()->raw(['type' => 'custom'])->toArray()['type'])
        ->toBe('custom');
});
