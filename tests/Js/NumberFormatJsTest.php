<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Support\NumberFormat;
use Symfony\Component\Process\Process;

covers(NumberFormat::class);

/**
 * Formats values with the browser formatter, run under Node (its Intl is the same ICU data a browser has).
 * Non-breaking spaces are folded to plain ones so the expectations stay readable.
 *
 * @param  list<array{0: NumberFormat, 1: string, 2: mixed}>  $cases  format, role, input
 * @return list<mixed>
 */
function browserFormat(array $cases): array
{
    $payload = array_map(static fn (array $case): array => [
        'spec' => $case[0]->marker($case[1])['__number__'],
        'input' => $case[2],
    ], $cases);

    $process = new Process(['node', __DIR__ . '/../Fixtures/number-format.mjs']);
    $process->setInput(json_encode($payload, JSON_THROW_ON_ERROR));
    $process->mustRun();

    $results = json_decode($process->getOutput(), true, flags: JSON_THROW_ON_ERROR);

    return array_map(static fn (mixed $value): mixed => is_string($value) ? preg_replace('/[\x{00A0}\x{202F}]/u', ' ', $value) : $value, $results);
}

beforeEach(function (): void {
    $finder = new Process(['node', '--version']);

    try {
        $finder->run();
    } catch (Throwable) {
        $this->markTestSkipped('Node is not available.');
    }

    if (! $finder->isSuccessful()) {
        $this->markTestSkipped('Node is not available.');
    }
});

it('writes plain numbers in the language of the reader', function (): void {
    expect(browserFormat([
        [NumberFormat::number()->locale('pl'), 'value', 18000],
        [NumberFormat::number()->locale('en'), 'value', 18000],
        [NumberFormat::number()->locale('pl'), 'value', 1234.5],
        [NumberFormat::number()->locale('en'), 'value', 1234.5],
        [NumberFormat::number()->locale('pl'), 'value', 1234567.891],
        [NumberFormat::number(decimals: 2)->locale('pl'), 'value', 1234.5],
        [NumberFormat::number()->locale('de'), 'value', 1234567.5],
    ]))->toBe(['18 000', '18,000', '1234,5', '1,234.5', '1 234 567,89', '1234,50', '1.234.567,5']);
});

it('writes money in the currency of the amount', function (): void {
    expect(browserFormat([
        [NumberFormat::currency('PLN')->locale('pl'), 'value', 1234.5],
        [NumberFormat::currency('EUR')->locale('en'), 'value', 1234.5],
        [NumberFormat::currency('USD')->locale('en'), 'value', 1234.5],
        [NumberFormat::currency('PLN')->locale('pl'), 'axis', 18000],
        [NumberFormat::currency('PLN')->locale('pl'), 'axis', 18000.5],
    ]))->toBe(['1234,50 zł', '€1,234.50', '$1,234.50', '18 000 zł', '18 000,50 zł']);
});

it('writes a percentage of a 0-100 value and of a 0-1 fraction', function (): void {
    expect(browserFormat([
        [NumberFormat::percent(fraction: false)->locale('pl'), 'value', 45],
        [NumberFormat::percent(fraction: true)->locale('pl'), 'value', 0.45],
        [NumberFormat::percent(fraction: false)->locale('en'), 'value', 12.345],
        [NumberFormat::percent(fraction: true, decimals: 1)->locale('en'), 'value', 0.1234],
    ]))->toBe(['45%', '45%', '12.35%', '12.3%']);
});

it('abbreviates large numbers when asked to', function (): void {
    expect(browserFormat([
        [NumberFormat::compact()->locale('pl'), 'axis', 18000],
        [NumberFormat::compact()->locale('en'), 'axis', 18000],
        [NumberFormat::compact()->locale('en'), 'axis', 1200000],
        [NumberFormat::currency('PLN')->compacted()->locale('en'), 'axis', 18000],
    ]))->toBe(['18 tys.', '18K', '1.2M', 'PLN 18K']);
});

it('appends a plain unit', function (): void {
    expect(browserFormat([
        [NumberFormat::number()->unit('kg')->locale('pl'), 'value', 12.5],
        [NumberFormat::number()->unit('szt.')->locale('pl'), 'axis', 18000],
    ]))->toBe(['12,5 kg', '18 000 szt.']);
});

it('leaves what is not a number alone and formats every number of a data item', function (): void {
    expect(browserFormat([
        [NumberFormat::number()->locale('pl'), 'value', 'Allegro'],
        [NumberFormat::number()->locale('pl'), 'value', [18000, 'x', 2.5]],
        [NumberFormat::number()->locale('pl'), 'value', null],
        [NumberFormat::number()->locale('pl'), 'label', ['value' => ['Mon', 18000], 'encode' => ['x' => [0], 'y' => [1]]]],
        [NumberFormat::number()->locale('pl'), 'label', ['value' => 18000]],
    ]))->toBe(['Allegro', '18 000, x, 2,5', '', '18 000', '18 000']);
});

it('falls back to the runtime locale for a tag it does not know', function (): void {
    expect(browserFormat([[NumberFormat::number()->locale('not a locale'), 'value', 1234.5]]))->toHaveCount(1);
});
