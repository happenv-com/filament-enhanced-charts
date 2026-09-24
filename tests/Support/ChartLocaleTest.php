<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Support\ChartLocale;

covers(ChartLocale::class);

/**
 * Every leaf key path of a locale array, lists counted as one leaf.
 *
 * @param  array<string, mixed>  $strings
 * @return array<string, mixed>
 */
function chartLocaleLeaves(array $strings, string $prefix = ''): array
{
    $leaves = [];

    foreach ($strings as $key => $value) {
        if (is_array($value) && ! array_is_list($value)) {
            $leaves += chartLocaleLeaves($value, $prefix . $key . '.');
        } else {
            $leaves[$prefix . $key] = $value;
        }
    }

    return $leaves;
}

it('ships an ECharts locale for every locale Filament ships', function (string $locale): void {
    $english = chartLocaleLeaves(require __DIR__ . '/../../resources/lang/en/locale.php');
    $file = __DIR__ . "/../../resources/lang/{$locale}/locale.php";

    expect($file)->toBeFile();

    $strings = require $file;
    $translated = chartLocaleLeaves($strings);

    expect(array_keys($translated))->toEqualCanonicalizing(array_keys($english));

    foreach ($english as $path => $value) {
        if (is_array($value)) {
            expect($translated[$path])->toBeArray()->toHaveCount(count($value), "{$locale}: {$path}");

            continue;
        }

        // A string may be empty in English (a separator or prefix) and filled
        // in a translation, but every {placeholder} has to survive.
        preg_match_all('/\{\w+\}/', (string) $value, $placeholders);
        foreach ($placeholders[0] as $placeholder) {
            expect($translated[$path])->toContain($placeholder);
        }
    }

    expect(array_filter($strings['time']['month']))->toHaveCount(12)
        ->and(array_filter($strings['time']['dayOfWeek']))->toHaveCount(7);
})->with(fn (): array => array_map(
    basename(...),
    glob(__DIR__ . '/../../vendor/filament/filament/resources/lang/*', GLOB_ONLYDIR) ?: [],
));

it('resolves the app locale by its ECharts name', function (): void {
    app()->setLocale('pl');
    expect(ChartLocale::current())
        ->name->toBe('PL')
        ->strings->time->month->{0}->toBe('Styczeń');

    app()->setLocale('pt_BR');
    expect(ChartLocale::current()['name'])->toBe('PT-BR');
});

it('falls back to English for a locale without a translation', function (): void {
    app()->setLocale('xx');

    expect(ChartLocale::current())
        ->name->toBe('XX')
        ->strings->toBe(require __DIR__ . '/../../resources/lang/en/locale.php');
});

it('fills the gaps of a partial translation with English', function (): void {
    app('translator')->addLines(['locale.legend.selector.all' => 'Tout'], 'zz', 'filament-enhanced-charts');
    app()->setLocale('zz');

    $strings = ChartLocale::current()['strings'];

    expect($strings['legend']['selector'])->toBe(['all' => 'Tout', 'inverse' => 'Inv'])
        ->and($strings['time']['month'][0])->toBe('January');
});

it('gives table cells only the time names', function (): void {
    app()->setLocale('de');

    expect(ChartLocale::currentTime())
        ->name->toBe('DE')
        ->strings->toHaveKeys(['time'])->toHaveCount(1)
        ->strings->time->month->{2}->toBe('März');
});

it('passes the locale to the widget and the table cells, which register it with ECharts', function (): void {
    $root = __DIR__ . '/../../';

    expect(file_get_contents($root . 'resources/views/widgets/components/chart.blade.php'))->toContain('locale: @js($locale)')
        ->and(file_get_contents($root . 'resources/views/widgets/echart-widget.blade.php'))->toContain('ChartLocale::current()')
        ->and(file_get_contents($root . 'src/Columns/EnhancedChartColumn.php'))->toContain('ChartLocale::currentTime()')
        ->and(file_get_contents($root . 'resources/js/shared.js'))->toContain('ApacheECharts.registerLocale(locale.name, locale.strings)')
        ->and(file_get_contents($root . 'resources/js/index.js'))->toContain('locale: useLocale(this.locale)')
        ->and(file_get_contents($root . 'resources/js/column.js'))->toContain('locale: useLocale(this.locale)');
});
