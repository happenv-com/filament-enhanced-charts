<?php

use BcMath\Number;
use Happenv\FilamentEnhancedCharts\Option\Series\BoxplotSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\CandlestickSeries;

it('builds a candlestick from OHLC rows with BcMath', function () {
    expect(CandlestickSeries::make()->data([[new Number('1.0'), 2, 0, 3]])->toArray())
        ->toBe(['type' => 'candlestick', 'data' => [[['__js__' => '1'], 2, 0, 3]]]);
});

it('applies itemStyle on a candlestick series', function () {
    expect(CandlestickSeries::make()->itemStyle(['color' => '#0f0'])->data([[1, 2, 0, 3]])->toArray())
        ->toBe(['type' => 'candlestick', 'itemStyle' => ['color' => '#0f0'], 'data' => [[1, 2, 0, 3]]]);
});

it('builds a boxplot from five-number rows', function () {
    expect(BoxplotSeries::make()->data([[0, 1, 2, 3, 4]])->toArray())
        ->toBe(['type' => 'boxplot', 'data' => [[0, 1, 2, 3, 4]]]);
});
