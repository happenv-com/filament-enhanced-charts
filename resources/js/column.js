import * as ApacheECharts from 'echarts'
import merge from 'lodash.merge'
import { reviveJs, applyTheme, panelBackground } from './shared.js'

// Minimal per-cell defaults: a body-appended tooltip so it escapes the
// overflow:hidden <td>. Deliberately NOT the widget's layoutDefaults
// (which forces confine:true and would clip the tooltip inside the cell).
function cellDefaults() {
    return { tooltip: { appendTo: 'body', confine: false } }
}

export default function echartsColumn({ options, renderer, width, height }) {
    let chart = null
    let intersectionObserver = null
    let resizeObserver = null
    let themeObserver = null

    return {
        options,
        renderer,
        width,
        height,

        init() {
            // Lazy: build the chart only when the cell scrolls into view, so a
            // 200-row page doesn't initialize 200 charts up front.
            intersectionObserver = new IntersectionObserver((entries) => {
                for (const entry of entries) {
                    if (entry.isIntersecting) {
                        intersectionObserver.disconnect()
                        intersectionObserver = null
                        this.build()
                        break
                    }
                }
            })
            intersectionObserver.observe(this.$refs.c)
        },

        build() {
            const el = this.$refs.c
            const base = merge({}, cellDefaults(), this.options)

            chart = ApacheECharts.init(el, null, {
                renderer: this.renderer,
                width: this.width,
                height: this.height,
            })
            chart.setOption(reviveJs(applyTheme(base, panelBackground(el))))

            resizeObserver = new ResizeObserver(() => {
                if (chart) {
                    chart.resize()
                }
            })
            resizeObserver.observe(el)

            themeObserver = new MutationObserver(() => {
                if (chart) {
                    chart.setOption(reviveJs(applyTheme(base, panelBackground(el))), { notMerge: true })
                }
            })
            themeObserver.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class'],
            })
        },

        destroy() {
            intersectionObserver?.disconnect()
            resizeObserver?.disconnect()
            themeObserver?.disconnect()
            chart?.dispose()
            chart = null
        },
    }
}
