<?php

declare(strict_types=1);

use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Option\Axis\ValueAxis;
use Happenv\FilamentEnhancedCharts\Option\Option;

it('serializes a formatter marker for the JS reviver to consume', function (): void {
    $json = json_encode(
        Option::make()->yAxis(ValueAxis::make()->axisLabel(RawJs::make('(v)=>v')))->toArray()
    );

    expect($json)->toContain('"__js__":"(v)=>v"');
});
