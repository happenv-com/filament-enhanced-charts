// Locale-aware number formatting for chart axes, tooltips and data labels.
//
// The PHP `NumberFormat` serialises into a `{__number__: spec}` marker (with
// the locale it was rendered for); `reviveJs` turns that marker into the
// function ECharts expects for the `role` the marker sits in:
//
//   axis   axisLabel.formatter       (value, index) => string
//   value  tooltip.valueFormatter    (value, dataIndex) => string
//   label  label.formatter           (params) => string
//
// Only finite numbers are formatted; every other value (a category name, a
// date string, null) is handed back untouched.

const instances = new Map()

function intl(locale, options) {
    const key = locale + '|' + JSON.stringify(options)
    let instance = instances.get(key)
    if (instance === undefined) {
        try {
            instance = new Intl.NumberFormat(locale || undefined, options)
        } catch (e) {
            // An unknown locale tag falls back to the runtime default.
            instance = new Intl.NumberFormat(undefined, options)
        }
        instances.set(key, instance)
    }
    return instance
}

function escapeHtml(text) {
    return String(text)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
}

// Intl options for a spec. On an axis a currency drops its fraction digits for
// a whole tick ("18 000 zł", not "18 000,00 zł") unless digits were asked for.
function intlOptions(spec, value, onAxis) {
    const style = spec.style || 'decimal'
    const options = { style }

    if (style === 'currency') {
        options.currency = spec.currency
    }

    if (spec.compact && style !== 'percent') {
        options.notation = 'compact'
        options.compactDisplay = 'short'
    }

    if (spec.decimals != null) {
        options.minimumFractionDigits = spec.decimals
        options.maximumFractionDigits = spec.decimals
    } else if (style === 'currency') {
        if (spec.compact || (onAxis && Number.isInteger(value))) {
            options.minimumFractionDigits = 0
            options.maximumFractionDigits = spec.compact ? 1 : 0
        }
    } else {
        options.minimumFractionDigits = 0
        options.maximumFractionDigits =
            spec.compact || style === 'percent' ? 1 : 2
        if (style === 'percent') {
            options.maximumFractionDigits = 2
        }
    }

    return options
}

export function formatNumber(spec, value, onAxis = false) {
    if (typeof value !== 'number' || !Number.isFinite(value)) {
        return value
    }

    const scaled =
        spec.style === 'percent' && !spec.fraction ? value / 100 : value
    let text = intl(spec.locale, intlOptions(spec, scaled, onAxis)).format(
        scaled,
    )

    if (spec.unit) {
        text += ' ' + spec.unit
    }

    return text
}

// Formats a value that may be a number or a list of values (a multi-dimension
// data item): every number in it is formatted, the rest kept as is.
function formatAny(spec, value, onAxis, html) {
    if (Array.isArray(value)) {
        return value
            .map((item) => formatAny(spec, item, onAxis, html))
            .join(', ')
    }
    const text = formatNumber(spec, value, onAxis)
    if (typeof text === 'string') {
        return html && typeof value === 'number' ? escapeHtml(text) : text
    }
    return text == null ? '' : String(text)
}

// The numeric part of a data-label's params: a plain value, or — for a series
// fed by a dataset — the entry its `encode` points at.
function labelValue(params) {
    const value = params.value
    if (!Array.isArray(value)) {
        return value
    }
    const encode = params.encode || {}
    const dims = [].concat(encode.y || [], encode.x || [], encode.value || [])
    for (let i = dims.length - 1; i >= 0; i--) {
        if (typeof value[dims[i]] === 'number') {
            return value[dims[i]]
        }
    }
    for (let i = value.length - 1; i >= 0; i--) {
        if (typeof value[i] === 'number') {
            return value[i]
        }
    }
    return value[value.length - 1]
}

export function numberFormatter(spec) {
    switch (spec.role) {
        case 'axis':
            return (value) => formatAny(spec, value, true, false)
        case 'label':
            return (params) => formatAny(spec, labelValue(params), false, false)
        default:
            return (value) => formatAny(spec, value, false, true)
    }
}
