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

it('dark mode restyles the tooltip and blends heatmap, treemap, sunburst and pie gaps with the panel', function (): void {
    $shared = file_get_contents(__DIR__ . '/../resources/js/shared.js');

    expect($shared)
        ->toContain('tooltip: {') // dark tooltip styling in the overrides
        ->toContain('backgroundColor')
        ->toContain('panelBackground') // cell/segment borders follow the card background
        ->toContain("s.type === 'heatmap'")
        ->toContain("const PANEL_BORDER_SERIES = ['treemap', 'sunburst', 'pie', 'funnel']");
});

it('dark mode styles the legend and title only when the chart has them', function (): void {
    // A bare `legend` override merged into an option without one would create a legend.
    $shared = file_get_contents(__DIR__ . '/../resources/js/shared.js');

    expect($shared)
        ->toContain('} else if (base.legend) {')
        ->toContain('} else if (base.title) {')
        ->not->toContain('legend: { textStyle: { color: text } },');
});

it('dark mode keeps explicit series label colours and leaves treemap labels alone', function (): void {
    $shared = file_get_contents(__DIR__ . '/../resources/js/shared.js');

    expect($shared)
        ->toContain("series.type === 'treemap'")
        ->toContain('const ownColor = series && series.label && series.label.color');
});

it('dark mode restyles slider data zooms', function (): void {
    $shared = file_get_contents(__DIR__ . '/../resources/js/shared.js');

    expect($shared)
        ->toContain('overrides.dataZoom')
        ->toContain("zoom && zoom.type === 'slider'");
});

it('gives the gauge anchor and uncoloured cut ticks the panel background', function (): void {
    $shared = file_get_contents(__DIR__ . '/../resources/js/shared.js');

    expect($shared)
        ->toContain('function gaugePanelStyle(series, panelBg)')
        ->toContain('style.anchor = { itemStyle: { color: panelBg } }')
        ->toContain('part.distance < 0');
});
