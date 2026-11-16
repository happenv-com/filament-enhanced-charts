import * as esbuild from 'esbuild'

/*
 * Builds the package's assets into resources/dist, which is committed and
 * served by Filament as is (see FilamentAsset registration in the service
 * provider). CI rebuilds and fails when the committed files differ.
 *
 *   npm run build   one minified build
 *   npm run dev     rebuild on change, with inline source maps
 *
 * Add an entry per asset: JavaScript is bundled for the browser, CSS files
 * are bundled too (their @imports inlined).
 */
const entries = [
    // The dashboard widget's Alpine component.
    { in: 'resources/js/index.js', out: 'filament-enhanced-charts' },
    // The table column's Alpine component (charts inside table cells).
    { in: 'resources/js/column.js', out: 'filament-enhanced-charts-column' },
    // The simplified world map (GeoJSON) for the world-map and flow-lines
    // presets. Registered as an Alpine component asset only so Filament
    // publishes it; it is imported on demand by a chart that uses it.
    {
        in: 'resources/js/maps/world.js',
        out: 'filament-enhanced-charts-map-world',
    },
]

const isDev = process.argv.includes('--dev')

const context = await esbuild.context({
    entryPoints: entries,
    outdir: 'resources/dist',
    bundle: true,
    // `neutral` + `mainFields` keeps Alpine components importable as ES modules.
    platform: 'neutral',
    mainFields: ['module', 'main'],
    target: ['es2020'],
    minify: !isDev,
    sourcemap: isDev ? 'inline' : false,
    sourcesContent: isDev,
    treeShaking: true,
    define: {
        'process.env.NODE_ENV': isDev ? `'development'` : `'production'`,
    },
    logLevel: 'info',
})

if (isDev) {
    await context.watch()
} else {
    await context.rebuild()
    await context.dispose()
}
