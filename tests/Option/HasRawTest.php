<?php

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;

$make = fn () => new class
{
    use HasRaw;

    public function out(array $base): array
    {
        return $this->mergeRaw($base);
    }
};

it('deep-merges associative arrays, override wins', function () use ($make) {
    $o = $make()->raw(['grid' => ['top' => 10], 'legend' => ['show' => false]]);
    expect($o->out(['grid' => ['left' => 8, 'top' => 4]]))
        ->toBe(['grid' => ['left' => 8, 'top' => 10], 'legend' => ['show' => false]]);
});

it('replaces lists wholesale rather than merging by index', function () use ($make) {
    $o = $make()->raw(['color' => ['#fff']]);
    expect($o->out(['color' => ['#000', '#111']]))->toBe(['color' => ['#fff']]);
});
