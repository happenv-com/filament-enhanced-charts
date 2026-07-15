<?php

it('the chart component applies explicit dark-mode overrides and observes theme toggles', function (): void {
    // The theming logic lives in the shared module (reused by widget + column);
    // the widget wires it in and owns the theme MutationObserver.
    $shared = file_get_contents(__DIR__ . '/../resources/js/shared.js');
    $index = file_get_contents(__DIR__ . '/../resources/js/index.js');

    expect($shared)
        ->toContain('isDarkMode')
        ->toContain('applyTheme')
        ->toContain("classList.contains('dark')");

    expect($index)
        ->toContain('applyTheme') // widget imports + wires the shared theming
        ->toContain('MutationObserver')
        ->not->toContain("ApacheECharts.init(\n");
});

it('dark mode restyles the tooltip and blends heatmap cell gaps with the panel', function (): void {
    $shared = file_get_contents(__DIR__ . '/../resources/js/shared.js');

    expect($shared)
        ->toContain('tooltip: {') // dark tooltip styling in the overrides
        ->toContain('backgroundColor')
        ->toContain('panelBackground') // heatmap cell borders follow the card background
        ->toContain('hasHeatmap');
});
