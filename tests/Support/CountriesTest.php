<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Support\Countries;

covers(Countries::class);

it('knows the countries of the map, with a label point inside the world', function (): void {
    expect(Countries::has('PL'))->toBeTrue()
        ->and(Countries::has('XX'))->toBeFalse()
        ->and(Countries::has('pl'))->toBeFalse()
        ->and(Countries::codes())->toContain('PL', 'DE', 'FR', 'NO', 'TW', 'XK')
        ->and(Countries::centroid('PL'))->toBe([19.49, 51.99])
        ->and(Countries::centroid('XX'))->toBeNull();

    foreach (Countries::codes() as $code) {
        [$lon, $lat] = Countries::centroid($code) ?? [999, 999];

        expect($lon)->toBeBetween(-180, 180)->and($lat)->toBeBetween(-90, 90);
    }
});

it('names a country in the given locale, falling back to English then to the code', function (): void {
    expect(Countries::name('PL'))->toBe('Poland')
        ->and(Countries::name('PL', 'de'))->toBe('Polen')
        ->and(Countries::name('PL', 'pl'))->toBe('Polska')
        ->and(Countries::name('XX'))->toBe('XX');
});

it('draws exactly the countries it names', function (): void {
    $map = json_decode((string) file_get_contents(__DIR__ . '/../../resources/geo/world-countries.json'), true, flags: JSON_THROW_ON_ERROR);
    $codes = array_column(array_column($map['features'], 'properties'), 'iso');

    expect($map['type'])->toBe('FeatureCollection')
        ->and($codes)->toHaveCount(count(array_unique($codes)))
        ->and($codes)->toEqualCanonicalizing(Countries::codes());
});
