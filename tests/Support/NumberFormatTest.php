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

it('writes the marker for the application locale at the time it is built', function (): void {
    $format = NumberFormat::currency('PLN');

    app()->setLocale('pl');
    $polish = $format->marker('value');
    app()->setLocale('en');
    $english = $format->marker('value');

    expect($polish['__number__']['locale'])->toBe('pl')
        ->and($english['__number__']['locale'])->toBe('en')
        ->and($polish['__number__']['role'])->toBe('value');
});

it('lets a pinned locale win over the application locale, with a BCP 47 tag', function (): void {
    app()->setLocale('pl');

    expect(NumberFormat::number()->locale('pt_BR')->marker('axis')['__number__']['locale'])->toBe('pt-BR')
        ->and(NumberFormat::number()->locale('de')->locale(null)->marker('axis')['__number__']['locale'])->toBe('pl');
});
