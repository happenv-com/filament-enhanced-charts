<?php

use Happenv\FilamentEnhancedCharts\Option\Component\Parallel;

covers(Parallel::class);

it('builds nothing by default', function () {
    expect(Parallel::make()->toArray())->toEqual([]);
});

it('builds layout edges via HasLayout', function () {
    expect(Parallel::make()->left(80)->right('10%')->top(60)->bottom(60)->toArray())
        ->toEqual(['left' => 80, 'right' => '10%', 'top' => 60, 'bottom' => 60]);
});

it('builds a parallelAxisDefault config', function () {
    expect(Parallel::make()->parallelAxisDefault(['type' => 'value', 'nameLocation' => 'end'])->toArray())
        ->toEqual(['parallelAxisDefault' => ['type' => 'value', 'nameLocation' => 'end']]);
});

it('lets raw() override a typed key', function () {
    expect(Parallel::make()->left(80)->raw(['left' => 40])->toArray())
        ->toEqual(['left' => 40]);
});
