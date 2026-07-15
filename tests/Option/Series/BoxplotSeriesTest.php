<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Enums\Orient;
use Happenv\FilamentEnhancedCharts\Option\Series\BoxplotSeries;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;

covers(BoxplotSeries::class);

it('builds a minimal boxplot series with data', function () {
    expect(BoxplotSeries::make()->data([[655, 850, 940, 980, 1070]])->toArray())
        ->toEqual(['type' => 'boxplot', 'data' => [[655, 850, 940, 980, 1070]]]);
});

it('applies itemStyle from a builder or an array', function () {
    expect(BoxplotSeries::make()->itemStyle(ItemStyle::make()->color('#f00'))->data([1])->toArray()['itemStyle'])
        ->toEqual(['color' => '#f00']);

    expect(BoxplotSeries::make()->itemStyle(['color' => '#0f0'])->data([1])->toArray()['itemStyle'])
        ->toEqual(['color' => '#0f0']);
});

it('applies layout from an Orient enum or a bare string', function () {
    expect(BoxplotSeries::make()->layout(Orient::Vertical)->data([1])->toArray()['layout'])->toBe('vertical');
    expect(BoxplotSeries::make()->layout('horizontal')->data([1])->toArray()['layout'])->toBe('horizontal');
});

it('applies boxWidth as a min/max pair', function () {
    expect(BoxplotSeries::make()->boxWidth([7, 50])->data([1])->toArray())
        ->toEqual(['type' => 'boxplot', 'data' => [1], 'boxWidth' => [7, 50]]);

    expect(BoxplotSeries::make()->boxWidth(['20%', '50%'])->data([1])->toArray()['boxWidth'])
        ->toEqual(['20%', '50%']);
});

it('lets raw() override a typed boxplot key', function () {
    expect(BoxplotSeries::make()->layout('horizontal')->raw(['layout' => 'vertical'])->toArray()['layout'])
        ->toBe('vertical');
});
