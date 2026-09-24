# Changelog

All notable changes to `filament-enhanced-charts` are documented in this file. Each section is written automatically from the GitHub release notes when a release is published — do not edit it by hand.

## v1.0.2 - 2026-08-18

## What's Changed
* Bump dependabot/fetch-metadata from 2.5.0 to 3.1.0 by @dependabot[bot] in https://github.com/happenv-com/filament-enhanced-charts/pull/2
* Bump actions/checkout from 6 to 7 by @dependabot[bot] in https://github.com/happenv-com/filament-enhanced-charts/pull/1
* fix: updateChart crashes the page when the chart is already disposed by @webard in https://github.com/happenv-com/filament-enhanced-charts/pull/3

## New Contributors
* @dependabot[bot] made their first contribution in https://github.com/happenv-com/filament-enhanced-charts/pull/2
* @webard made their first contribution in https://github.com/happenv-com/filament-enhanced-charts/pull/3

**Full Changelog**: https://github.com/happenv-com/filament-enhanced-charts/compare/v1.0.1...v1.0.2

## v1.0.1 - 2026-07-23

CI fixes on top of v1.0.0 — no package code changes.

- Tests matrix: dropped Laravel 11 (its framework releases are blocked by composer's security-advisory policy, so CI could not resolve them); Laravel 12 rows unchanged. Laravel 13 support is exercised end-to-end by the consuming application's suite.
- PHPStan: documented ignore for the `view-string` default-value check on `EnhancedChartWidget::$view` (the package's own view namespace does not exist inside PHPStan's application sandbox; the view is covered by widget render tests).

All checks green on `1.x`: Tests (8 matrix jobs), PHPStan, Pint.

## v1.0.0 - 2026-07-23

First stable release of **filament-enhanced-charts** — Apache ECharts (v6) integration for Filament v4/v5, continuing the former `happenv-com/filament-echarts` under its new name.

## Highlights

- **Fully typed option model** — `EnhancedChartWidget` with a single `getOption(): Option`; 22 series types, axes, and 28 components as fluent, typed builders. `->raw()` remains only as a last-resort escape hatch.
- **Native `BcMath\Number` & `RawJs` support** — values and JS formatters serialize as `{__js__}` markers with exact decimal literals, rebuilt client-side by a reviver (safe `@js` attribute embedding included).
- **Eloquent bridges** — `ChartData::fromPairs()/fromTimeSeries()` (laravel-trend compatible), `Dataset::fromModels()`, and the `HasChartData` shortcut for Line/Area/Bar/Pie.
- **Widget toolkit** — select & schema filters, polling with options-hash dirty-check, deferred loading, dark mode with Filament theme observer, GeoJSON maps, page-scroll-friendly wheel handling, `EnhancedChartColumn` table sparklines/candles/pies.
- **Testing** — Livewire `Testable` mixin (`assertChartOptions`, `assertChartSeriesCount`, `assertChartHasSeries`); 702 package tests.

## Upgrading from filament-echarts v2

See [UPGRADING.md](https://github.com/happenv-com/filament-enhanced-charts/blob/1.x/UPGRADING.md) — package/class renames, `getOptions(): array` → `getOption(): Option`, `extraJsOptions()` removal (formatters live in the option tree), and test-assertion notes for `{__js__}` markers.
