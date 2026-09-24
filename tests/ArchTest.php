<?php

use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Happenv\FilamentEnhancedCharts\Widgets\EnhancedChartWidget;

arch()->preset()->php();

// EnhancedChartWidget::hashOptions() uses md5() as a cheap change-detection
// fingerprint of the chart options, not for anything security-related.
arch()->preset()->security()->ignoring([
    EnhancedChartWidget::class,
]);

arch('no debugging calls')
    ->expect(['dd', 'dump', 'ray', 'var_dump'])
    ->not->toBeUsed();

arch('option nodes implement the Node contract')
    ->expect('Happenv\FilamentEnhancedCharts\Option')
    ->classes()
    ->toImplement(Node::class)
    ->ignoring([
        Node::class,
        Normalize::class,
    ]);
