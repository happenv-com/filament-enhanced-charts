<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Option\Component\Polar;

covers(Polar::class);

it('builds a minimal polar with no options set', function (): void {
    expect(Polar::make()->toArray())->toEqual([]);
});

it('builds a polar with center and a percentage radius', function (): void {
    expect(Polar::make()->center(['50%', '50%'])->radius('75%')->toArray())
        ->toEqual([
            'center' => ['50%', '50%'],
            'radius' => '75%',
        ]);
});

it('accepts an [inner, outer] radius pair', function (): void {
    expect(Polar::make()->radius([20, '80%'])->toArray())
        ->toEqual(['radius' => [20, '80%']]);
});

it('lets raw() override a typed key', function (): void {
    expect(Polar::make()->radius('75%')->raw(['radius' => '50%'])->toArray())
        ->toEqual(['radius' => '50%']);
});
