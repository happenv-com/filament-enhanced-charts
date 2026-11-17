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
// data item). Every number is formatted, the rest kept as is. `spec.each`
// gives the dimensions of such an item a format of their own (a point with
// money on x and a percentage on y); a missing entry falls back to the spec.
// ECharts escapes what a tooltip formatter returns, so nothing is escaped here.
function formatAny(spec, value, onAxis) {
    if (Array.isArray(value)) {
        return value.map((item, index) => {
            const own = spec.each && spec.each[index]
            return formatAny(
                own ? { locale: spec.locale, ...own } : spec,
                item,
                onAxis,
            )
        })
    }
    const text = formatNumber(spec, value, onAxis)
    return text == null ? '' : String(text)
}

// The number a data label shows: the value of the item, or — for an item with
// several dimensions (a point, a heatmap cell, a dataset row or an object row)
// — the dimension the series encodes as its value, then as y, then as x. The
// first of those that holds a number wins, so a timestamp on x never beats the
// reading on y.
function labelValue(params) {
    let value = params.value
    if (value && typeof value === 'object' && !Array.isArray(value)) {
        value = (params.dimensionNames || []).map((name) => value[name])
    }
    if (!Array.isArray(value)) {
        return value
    }
    const encode = params.encode || {}
    for (const dim of [].concat(
        encode.value || [],
        encode.y || [],
        encode.x || [],
    )) {
        if (typeof value[dim] === 'number') {
            return value[dim]
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
            return (value) => joinList(formatAny(spec, value, true))
        case 'label':
            return (params) =>
                joinList(formatAny(spec, labelValue(params), false))
        default:
            return (value) => formatAny(spec, value, false)
    }
}

// An axis or a label shows a single string; a tooltip may be given a list.
function joinList(formatted) {
    return Array.isArray(formatted) ? formatted.join(', ') : formatted
}
