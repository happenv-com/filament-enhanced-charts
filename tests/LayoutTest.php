<?php

declare(strict_types=1);

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

it('reserves room beneath the plot for a horizontal slider data zoom', function (): void {
    $js = file_get_contents(__DIR__ . '/../resources/js/index.js');

    expect($js)
        ->toContain("zoom.type === 'slider'")
        ->toContain('sliderRoom');
});

it('hides overlapping time-axis labels', function (): void {
    $js = file_get_contents(__DIR__ . '/../resources/js/index.js');

    expect($js)
        ->toContain("for (const key of ['xAxis', 'yAxis', 'singleAxis'])")
        ->toContain('axisLabel: { hideOverlap: true }')
        ->toContain('defaults[key] = axis.map(timeAxisStyle)'); // multi-axis charts too
});

it('resizes the chart only when its size really changes, so the initial animation runs', function (): void {
    $shared = file_get_contents(__DIR__ . '/../resources/js/shared.js');

    expect($shared)
        ->toContain('export function observeResize(el, onResize)')
        ->toContain('if (next[0] !== size[0] || next[1] !== size[1])');

    foreach (['index.js', 'column.js'] as $file) {
        expect(file_get_contents(__DIR__ . '/../resources/js/' . $file))
            ->toContain('observeResize(')
            ->not->toContain('new ResizeObserver(');
    }
});
