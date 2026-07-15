<?php

it('the chart component injects cartesian layout defaults (tight grid + placed axis names)', function (): void {
    $js = file_get_contents(__DIR__ . '/../resources/js/index.js');

    expect($js)
        ->toContain('layoutDefaults')
        ->toContain('containLabel')
        ->toContain('nameLocation')
        ->toContain('nameRotate') // value-axis title rendered vertically
        // Defaults are merged UNDER the widget options so any widget can override.
        ->toContain('merge({}, layoutDefaults(');
});

it('confines tooltips so they are not clipped by the overflow:hidden container', function (): void {
    $js = file_get_contents(__DIR__ . '/../resources/js/index.js');

    expect($js)->toContain('confine: true');
});

it('auto-tunes the vertical axis title gap from the measured label width', function (): void {
    $js = file_get_contents(__DIR__ . '/../resources/js/index.js');

    expect($js)
        ->toContain('valueAxisLabelWidth')
        ->toContain('tuneAxisNames')
        ->toContain('measureText'); // measures real rendered label width
});
