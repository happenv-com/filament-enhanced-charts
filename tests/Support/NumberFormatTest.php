<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Support\NumberFormat;

covers(NumberFormat::class);

it('serialises only what was set', function (): void {
    expect(NumberFormat::number()->toArray())->toBe(['style' => 'decimal'])
        ->and(NumberFormat::number(2)->toArray())->toBe(['style' => 'decimal', 'decimals' => 2])
        ->and(NumberFormat::currency('pln')->toArray())->toBe(['style' => 'currency', 'currency' => 'PLN'])
        ->and(NumberFormat::percent(fraction: true)->toArray())->toBe(['style' => 'percent', 'fraction' => true])
        ->and(NumberFormat::percent(fraction: false)->toArray())->toBe(['style' => 'percent', 'fraction' => false])
        ->and(NumberFormat::compact()->unit('kg')->locale('pl')->toArray())->toBe(['style' => 'decimal', 'compact' => true, 'unit' => 'kg', 'locale' => 'pl']);
});

it('is immutable', function (): void {
    $base = NumberFormat::number();
    $base->unit('kg');
    $base->locale('pl');

    expect($base->toArray())->toBe(['style' => 'decimal']);
});

it('rebuilds itself from its array', function (NumberFormat $format): void {
    expect(NumberFormat::fromArray($format->toArray())->equals($format))->toBeTrue();
})->with([
    'number' => fn (): NumberFormat => NumberFormat::number(1)->unit('kg'),
    'currency' => fn (): NumberFormat => NumberFormat::currency('EUR')->compacted()->locale('de'),
    'percent' => fn (): NumberFormat => NumberFormat::percent(fraction: true, decimals: 1),
]);

it('refuses a malformed currency code, an unknown style and absurd decimals', function (): void {
    expect(fn (): NumberFormat => NumberFormat::currency('zloty'))->toThrow(InvalidArgumentException::class)
        ->and(fn (): NumberFormat => NumberFormat::fromArray(['style' => 'scientific']))->toThrow(InvalidArgumentException::class)
        ->and(fn (): NumberFormat => NumberFormat::number(-1))->toThrow(InvalidArgumentException::class)
        ->and(fn (): NumberFormat => NumberFormat::number(21))->toThrow(InvalidArgumentException::class);
});

it('infers a format from a free-text unit', function (?string $unit, ?array $expected): void {
    expect(NumberFormat::fromUnit($unit)?->toArray())->toBe($expected);
})->with([
    'none' => [null, null],
    'blank' => ['  ', null],
    'currency' => ['PLN', ['style' => 'currency', 'currency' => 'PLN']],
    'percent' => ['%', ['style' => 'percent', 'fraction' => false]],
    'plain unit' => ['pcs', ['style' => 'decimal', 'unit' => 'pcs']],
    'a three-letter unit that is no currency' => ['PCS', ['style' => 'decimal', 'unit' => 'PCS']],
    'a lowercase code is a unit' => ['pln', ['style' => 'decimal', 'unit' => 'pln']],
]);

it('leaves the locale out of a marker until the option is built, then lays in the application locale of that moment', function (): void {
    $marker = ['series' => [['label' => ['formatter' => NumberFormat::currency('PLN')->marker('label')]]]];

    app()->setLocale('pl');
    $polish = NumberFormat::resolveLocales($marker);
    app()->setLocale('en');
    $english = NumberFormat::resolveLocales($marker);

    expect($marker['series'][0]['label']['formatter']['__number__'])->not->toHaveKey('locale')
        ->and($polish['series'][0]['label']['formatter']['__number__']['locale'])->toBe('pl')
        ->and($english['series'][0]['label']['formatter']['__number__']['locale'])->toBe('en');
});

it('lets a pinned locale win over the application locale, with a BCP 47 tag', function (): void {
    app()->setLocale('pl');

    expect(NumberFormat::resolveLocales(NumberFormat::number()->locale('pt_BR')->marker('axis'))['__number__']['locale'])->toBe('pt-BR')
        ->and(NumberFormat::resolveLocales(NumberFormat::number()->locale('de')->locale(null)->marker('axis'))['__number__']['locale'])->toBe('pl');
});

it('writes a marker for a multi-dimension item with a format per dimension, each in the locale', function (): void {
    app()->setLocale('de');

    $marker = NumberFormat::resolveLocales(NumberFormat::dimensionsMarker([NumberFormat::currency('PLN'), null, NumberFormat::percent(fraction: true)->locale('pl')]));

    expect($marker['__number__']['each'])->toBe([
        ['style' => 'currency', 'currency' => 'PLN', 'locale' => 'de'],
        null,
        ['style' => 'percent', 'fraction' => true, 'locale' => 'pl'],
    ])->and($marker['__number__']['role'])->toBe('value');
});
