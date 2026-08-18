import * as ApacheECharts from 'echarts'
import merge from 'lodash.merge'
import { reviveJs, applyTheme, panelBackground } from './shared.js'

// A cartesian axis config is either a single object or, for multi-axis charts
// (dual-Y etc.), a list of them. These two helpers normalize that shape so the
// rest of the file doesn't have to.
function hasAxis(axis) {
    return Array.isArray(axis) ? axis.length > 0 : axis != null
}

function axisHasName(axis) {
    if (Array.isArray(axis)) {
        return axis.some((a) => a && a.name)
    }
    return !!(axis && axis.name)
}

// Sensible layout defaults so every chart gets a non-clipped tooltip, and every
// cartesian chart gets tight margins and well-placed axis titles, without each
// widget repeating the config. Merged UNDER the widget options, so any widget
// can override per key.
function layoutDefaults(base) {
    // The chart container is overflow:hidden, so an edge tooltip gets clipped —
    // confine keeps it inside the box. Applies to every chart type.
    const defaults = { tooltip: { confine: true } }

    const hasX = hasAxis(base.xAxis)
    const hasY = hasAxis(base.yAxis)

    // Non-cartesian charts (pie/treemap/gauge/polar) keep only the tooltip fix.
    if (!hasX && !hasY) {
        return defaults
    }

    // A legend docked to `left`/`right` (or explicitly `orient: 'vertical'`)
    // sits beside the plot rather than above/below it, so it should widen the
    // grid on that side instead of stealing top/bottom margin.
    const hasSideLegend =
        !!base.legend &&
        base.legend.show !== false &&
        (base.legend.left !== undefined || base.legend.right !== undefined || base.legend.orient === 'vertical')
    const legendOnRight = hasSideLegend && base.legend.right !== undefined
    const legendOnLeft = hasSideLegend && !legendOnRight

    // A legend without an explicit `bottom` sits at the top (ECharts default);
    // one with `bottom` sits beneath the plot. Reserve room for whichever applies.
    const hasTopLegend =
        !hasSideLegend && base.legend && base.legend.show !== false && base.legend.bottom === undefined
    const hasBottomLegend =
        !hasSideLegend && base.legend && base.legend.show !== false && base.legend.bottom !== undefined

    // `containLabel` reserves room for axis LABELS but not axis NAMES, so a side
    // that carries a name needs a little extra margin. The vertical value-axis
    // title's exact gap is tuned post-render from the real label width (see
    // tuneAxisNames) — these are just the starting values. Tight everywhere else.
    // For a multi-axis chart (an array), reserve the margin if ANY axis in the
    // array carries a name — pragmatic rather than exact per-axis placement.
    const hasXName = hasX && axisHasName(base.xAxis)
    const hasYName = hasY && axisHasName(base.yAxis)

    defaults.grid = {
        left: (hasYName ? 28 : 8) + (legendOnLeft ? 80 : 0),
        right: 24 + (legendOnRight ? 80 : 0),
        top: hasTopLegend ? 40 : 16,
        // Room for the x-axis title and/or a bottom legend, with a gap above it.
        bottom: (hasXName ? 40 : 8) + (hasBottomLegend ? 34 : 0),
        containLabel: true,
    }

    // Value-axis title: centred and vertical along the left edge. Only for a
    // single y-axis — a multi-axis chart merges a plain object into an axis
    // ARRAY unpredictably, and each axis usually wants its own placement anyway
    // (set explicitly via ValueAxis::make()->name(...) etc.).
    if (hasYName && !Array.isArray(base.yAxis)) {
        defaults.yAxis = { nameLocation: 'middle', nameRotate: 90, nameGap: 40 }
    }

    // Category-axis title: centred beneath the axis (not floating at the end).
    // Same single-axis caveat as above.
    if (hasXName && !Array.isArray(base.xAxis)) {
        defaults.xAxis = { nameLocation: 'middle', nameGap: 26 }
    }

    return defaults
}

// ECharts has no "auto" nameGap, so we measure the real rendered width of the
// value-axis labels and set the vertical title's gap to clear them — no more
// title overlapping the numbers, whatever their magnitude. Best-effort: any
// failure falls back to the static nameGap from layoutDefaults.
let measureCtx = null

function valueAxisLabelWidth(chart, axisIndex, axisConfig) {
    try {
        const model = chart.getModel().getComponent('yAxis', axisIndex)
        if (!model || !model.axis || !model.axis.scale || !model.axis.scale.getTicks) {
            return null
        }

        const axis = model.axis
        const ticks = axis.scale.getTicks()
        if (!ticks || !ticks.length) {
            return null
        }

        const labelOpt = (axisConfig && axisConfig.axisLabel) || {}
        let formatter = labelOpt.formatter
        if (formatter && typeof formatter === 'object' && formatter.__js__) {
            try { formatter = new Function('return (' + formatter.__js__ + ')')() } catch (e) { formatter = undefined }
        }
        const fontSize = labelOpt.fontSize || 12

        if (measureCtx === null) {
            measureCtx = document.createElement('canvas').getContext('2d')
        }
        measureCtx.font = fontSize + 'px ' + (labelOpt.fontFamily || 'sans-serif')

        let max = 0
        for (const tick of ticks) {
            const value = tick && typeof tick === 'object' && 'value' in tick ? tick.value : tick
            let label
            try {
                if (typeof formatter === 'function') {
                    label = formatter(value)
                } else if (axis.scale.getLabel) {
                    label = axis.scale.getLabel(tick)
                } else {
                    label = String(value)
                }
            } catch (e) {
                label = String(value)
            }
            max = Math.max(max, measureCtx.measureText(String(label)).width)
        }

        return max || null
    } catch (e) {
        return null
    }
}

// ECharts maps are registered globally by name via echarts.registerMap; track
// which we've already fetched+registered so re-renders/polling don't refetch.
const registeredMaps = new Set()

export default function echarts({ options, chartId, renderer, maps }) {
    let chart = null
    let resizeObserver = null
    return {
        options,
        chartId,
        renderer,
        maps: maps || {},
        baseOptions: null,
        themeObserver: null,

        init() {
            this.$wire.$on('updateOptions', ({ options }) => {
                this.updateChart(options)
            })

            Alpine.effect(() => {
                this.$nextTick(() => {
                    if (chart === null) {
                        this.initChart()
                    } else {
                        this.updateChart(this.options)
                    }
                })
            })

            // Re-apply colors when Filament toggles the `dark` class on <html>.
            this.themeObserver = new MutationObserver(() => {
                if (chart && this.baseOptions) {
                    chart.setOption(
                        reviveJs(applyTheme(this.baseOptions, panelBackground(this.chartId))),
                        { notMerge: true },
                    )
                }
            })
            this.themeObserver.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class'],
            })

            document
                .querySelectorAll('.fi-wi-chart-filter > .fi-dropdown-panel')
                .forEach((el) => {
                    el.style.zIndex = '20'
                })
        },

        initChart: function () {
            this.baseOptions = merge({}, layoutDefaults(this.options), this.options)

            chart = ApacheECharts.init(document.querySelector(this.chartId), null, { renderer: this.renderer })

            // Register any GeoJSON maps the option references, THEN paint — a
            // map/geo series draws nothing until echarts.registerMap has run.
            this.registerMaps().then(() => {
                if (!chart) {
                    return
                }
                chart.setOption(reviveJs(applyTheme(this.baseOptions, panelBackground(this.chartId))))
                this.tuneAxisNames()
            })

            resizeObserver = new ResizeObserver((entries) => {
                if (chart) {
                    for (const entry of entries) {
                        chart.resize()
                    }
                }
            })
            resizeObserver.observe(document.querySelector(this.chartId))
        },

        // Alpine calls this when the component element is removed (SPA
        // navigation, Livewire morph) — without it every dashboard visit
        // leaks a document-wide MutationObserver, a ResizeObserver and the
        // ECharts instance.
        destroy() {
            this.themeObserver?.disconnect()
            this.themeObserver = null
            resizeObserver?.disconnect()
            resizeObserver = null
            chart?.dispose()
            chart = null
        },

        // Fetch + echarts.registerMap() every map name referenced by a `map`
        // series or a `geo` component, using the {name: url} map passed from the
        // widget. Idempotent across widgets via the module-level registeredMaps.
        async registerMaps() {
            const referenced = new Set()
            const opt = this.options || {}
            if (Array.isArray(opt.series)) {
                for (const s of opt.series) {
                    if (s && s.type === 'map' && s.map) {
                        referenced.add(s.map)
                    }
                }
            }
            if (Array.isArray(opt.geo)) {
                for (const g of opt.geo) {
                    if (g && g.map) {
                        referenced.add(g.map)
                    }
                }
            } else if (opt.geo && opt.geo.map) {
                referenced.add(opt.geo.map)
            }
            for (const name of referenced) {
                if (registeredMaps.has(name) || !this.maps[name]) {
                    continue
                }
                try {
                    const response = await fetch(this.maps[name])
                    ApacheECharts.registerMap(name, await response.json())
                    registeredMaps.add(name)
                } catch (e) {
                    // Soft-fail: the chart still renders, just without the geometry.
                }
            }
        },

        // An `updateOptions` event can land when there is no chart to paint:
        // `destroy()` nulls it when the component leaves the DOM (wire:navigate,
        // Livewire morph), yet a round-trip already in flight still resolves onto
        // the old listener. Every other reader here guards; this one did not, so
        // the widget's own refresh crashed the page with "Cannot read properties
        // of null (reading 'setOption')". Dropping the update is the whole fix —
        // a chart that no longer exists has nothing to show, and a re-mounted
        // component paints from `this.options` in `initChart()`.
        updateChart(options) {
            if (chart === null) {
                return
            }

            this.baseOptions = merge({}, layoutDefaults(options), options)
            chart.setOption(reviveJs(applyTheme(this.baseOptions, panelBackground(this.chartId))))
            this.tuneAxisNames()
        },

        // Set the vertical value-axis title's gap from its real label width so it
        // never overlaps the numbers. Runs in the same tick as setOption, so the
        // corrected layout is the only thing painted (no flash). No-op if there's
        // no vertical title or the measurement isn't available.
        tuneAxisNames() {
            const base = this.baseOptions
            if (!chart || !base || !base.yAxis) {
                return
            }

            // Multi-axis chart: tune each y-axis that carries a name independently,
            // matching each to its ECharts component by array index.
            if (Array.isArray(base.yAxis)) {
                let changed = false
                base.yAxis.forEach((axisConfig, index) => {
                    if (!axisConfig || !axisConfig.name) {
                        return
                    }

                    const width = valueAxisLabelWidth(chart, index, axisConfig)
                    if (!width) {
                        return
                    }

                    const nameGap = Math.round(width) + 14
                    if (Math.abs((axisConfig.nameGap || 0) - nameGap) > 2) {
                        axisConfig.nameGap = nameGap
                        changed = true
                    }
                })

                if (changed) {
                    chart.setOption(reviveJs(applyTheme(base, panelBackground(this.chartId))))
                }
                return
            }

            if (!base.yAxis.name) {
                return
            }

            const width = valueAxisLabelWidth(chart, 0, base.yAxis)
            if (!width) {
                return
            }

            const nameGap = Math.round(width) + 14
            if (Math.abs((base.yAxis.nameGap || 0) - nameGap) > 2) {
                base.yAxis.nameGap = nameGap
                chart.setOption(reviveJs(applyTheme(base, panelBackground(this.chartId))))
            }
        },
    }
}
