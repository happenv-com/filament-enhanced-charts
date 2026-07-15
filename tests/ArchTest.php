<?php

it('will not use debugging functions')
    ->expect(['dd', 'dump', 'ray'])
    ->each->not->toBeUsed();

arch('option nodes implement the Node contract')
    ->expect('Happenv\FilamentEnhancedCharts\Option')
    ->classes()
    ->toImplement('Happenv\FilamentEnhancedCharts\Option\Contracts\Node')
    ->ignoring([
        'Happenv\FilamentEnhancedCharts\Option\Contracts\Node',
        'Happenv\FilamentEnhancedCharts\Option\Support\Normalize',
    ]);
