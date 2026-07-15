import merge from 'lodash.merge'
import * as ApacheECharts from 'echarts'

// Formatters and custom-series renderItem functions ported from the ECharts
// gallery reference the global `echarts` namespace (echarts.format.addCommas,
// echarts.graphic.clipRectByRect, echarts.number.linearMap, echarts.color.lift,
// …). reviveJs builds those functions with `new Function`, whose body runs in
// GLOBAL scope — it can't see this module's `ApacheECharts` binding, only
// globals. Expose the module as the conventional `echarts` global (exactly what
// ECharts' own UMD/CDN build does) so those ported expressions resolve.
if (typeof globalThis !== 'undefined' && globalThis.echarts == null) {
    globalThis.echarts = ApacheECharts
}

// Replace {__js__: '<expr>'} markers with real JS values (functions or numeric
// literals) emitted by the PHP option model. Returns a fresh structure so the
// marker-form baseOptions stays reusable for re-theming. Mirrors the security
// posture of the old extraJsOptions channel: expressions originate from our own
// server-side option model, not user input.
export function reviveJs(node) {
    if (Array.isArray(node)) {
        return node.map(reviveJs)
    }
    if (node && typeof node === 'object') {
        const keys = Object.keys(node)
        if (keys.length === 1 && keys[0] === '__js__') {
            try {
                return new Function('return (' + node.__js__ + ')')()
            } catch (e) {
                return node.__js__
            }
        }
        const out = {}
        for (const key of keys) {
            out[key] = reviveJs(node[key])
        }
        return out
    }
    return node
}

export function isDarkMode() {
    return document.documentElement.classList.contains('dark')
}

// Explicit, minimal dark overrides — NOT ECharts' built-in 'dark' theme (which would
// also change tooltip/background/series defaults and drift from visual parity).
export function darkOverrides(base) {
    const text = '#d1d5db' // gray-300
    const axis = '#9ca3af' // gray-400
    const split = 'rgba(255, 255, 255, 0.08)'

    const axisStyle = {
        axisLabel: { color: axis },
        axisLine: { lineStyle: { color: split } },
        splitLine: { lineStyle: { color: split } },
    }

    const overrides = {
        textStyle: { color: text },
        legend: { textStyle: { color: text } },
        title: { textStyle: { color: text }, subtextStyle: { color: axis } },
        tooltip: {
            backgroundColor: '#1f2937', // gray-800
            borderColor: '#374151', // gray-700
            textStyle: { color: text },
        },
    }

    // Only touch axes when the chart actually has them (pie/treemap/gauge have none),
    // so we never inject a phantom cartesian axis. A multi-axis chart (dual-Y etc.)
    // gets one override entry per axis — ECharts matches an axis-option ARRAY to the
    // axis ARRAY by index.
    // Cartesian + polar + single axes all carry labels that need lightening on
    // a dark panel. Each may be a single object or (multi-axis) an array.
    for (const key of ['xAxis', 'yAxis', 'angleAxis', 'radiusAxis', 'singleAxis']) {
        const axis = base[key]
        if (Array.isArray(axis)) {
            overrides[key] = axis.map(() => axisStyle)
        } else if (axis) {
            overrides[key] = axisStyle
        }
    }

    // Parallel coordinates: the parallelAxis array AND the parallel component's
    // parallelAxisDefault carry axis names/labels/lines that need lightening on
    // a dark panel (darkOverrides otherwise only covers cartesian/polar/single
    // axes). The axis LINE stays visible (gray) — it's the main vertical axis,
    // not a faint gridline.
    const parallelAxisStyle = {
        nameTextStyle: { color: text },
        axisLabel: { color: axis },
        axisLine: { lineStyle: { color: axis } },
        axisTick: { lineStyle: { color: split } },
        splitLine: { lineStyle: { color: split } },
    }
    if (base.parallelAxis) {
        overrides.parallelAxis = Array.isArray(base.parallelAxis)
            ? base.parallelAxis.map(() => parallelAxisStyle)
            : parallelAxisStyle
    }
    if (base.parallel) {
        overrides.parallel = { parallelAxisDefault: parallelAxisStyle }
    }

    // visualMap piece/range labels also sit on the panel.
    if (base.visualMap) {
        const visualMapStyle = { textStyle: { color: text } }
        overrides.visualMap = Array.isArray(base.visualMap)
            ? base.visualMap.map(() => visualMapStyle)
            : visualMapStyle
    }

    // Series labels spill onto the dark panel (sunburst leaf labels, pie/graph
    // "outside" labels, scatter point labels, …). Their light-mode default is
    // dark text with a light "readability halo" — on a dark panel that turns
    // invisible and the white halo glows. Lighten the text and FLIP the halo
    // dark: light text + a dark outline reads on both a bright series element
    // (a coloured arc/slice) AND the dark panel, without the ugly white glow.
    // Only color + text-border are set — position/formatter/rich/etc. untouched,
    // and this merges by index with any per-series override above (e.g. heatmap).
    if (Array.isArray(base.series)) {
        const labelStyle = { color: text, textBorderColor: 'rgba(0, 0, 0, 0.55)' }
        overrides.series = base.series.map((series, i) =>
            merge({}, (overrides.series && overrides.series[i]) || {}, {
                label: { ...labelStyle },
                labelLine: { lineStyle: { color: axis } },
            }),
        )
    }

    return overrides
}

// The first non-transparent background up the DOM from the chart — i.e. the card
// the chart sits in. Used so heatmap cell gaps blend with the panel in any theme.
// Accepts either a CSS selector (widget passes its chartId) or a DOM element
// (the column passes the cell element directly).
export function panelBackground(selectorOrElement) {
    let node = typeof selectorOrElement === 'string'
        ? document.querySelector(selectorOrElement)
        : selectorOrElement
    while (node) {
        const bg = getComputedStyle(node).backgroundColor
        if (bg && bg !== 'transparent' && bg !== 'rgba(0, 0, 0, 0)') {
            return bg
        }
        node = node.parentElement
    }
    return null
}

export function hasHeatmap(base) {
    return Array.isArray(base.series) && base.series.some((s) => s && s.type === 'heatmap')
}

export function applyTheme(base, panelBg) {
    let overrides = {}

    // Heatmaps draw white gaps between cells by default; recolour the cell borders
    // to the panel background so they blend in both light and dark mode.
    if (panelBg && hasHeatmap(base)) {
        overrides.series = base.series.map((s) =>
            s && s.type === 'heatmap' ? { itemStyle: { borderColor: panelBg } } : {},
        )
    }

    if (isDarkMode()) {
        overrides = merge(overrides, darkOverrides(base))
    }

    return Object.keys(overrides).length ? merge({}, base, overrides) : base
}
