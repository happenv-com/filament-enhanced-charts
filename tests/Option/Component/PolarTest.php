<?php

use Happenv\FilamentEnhancedCharts\Option\Component\Polar;

covers(Polar::class);

it('builds a minimal polar with no options set', function () {
    expect(Polar::make()->toArray())->toEqual([]);
});

it('builds a polar with center and a percentage radius', function () {
    expect(Polar::make()->center(['50%', '50%'])->radius('75%')->toArray())
        ->toEqual([
            'center' => ['50%', '50%'],
            'radius' => '75%',
        ]);
});

it('accepts an [inner, outer] radius pair', function () {
    expect(Polar::make()->radius([20, '80%'])->toArray())
        ->toEqual(['radius' => [20, '80%']]);
});

it('lets raw() override a typed key', function () {
    expect(Polar::make()->radius('75%')->raw(['radius' => '50%'])->toArray())
        ->toEqual(['radius' => '50%']);
});
