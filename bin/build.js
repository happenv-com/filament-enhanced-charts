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
