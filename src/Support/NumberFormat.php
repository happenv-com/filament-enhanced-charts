<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Support;

use InvalidArgumentException;

/**
 * How a number is written on a chart — an axis, a tooltip or a data label — in
 * the language of the reader: `18 000` in Polish, `18,000` in English.
 *
 * A format is a small immutable value. It serialises into the option as a
 * marker carrying its settings and the locale to format for, and the browser
 * turns that into an `Intl.NumberFormat` call, so nobody has to write
 * JavaScript. The locale is the application locale at the moment the option is
 * built (`app()->getLocale()`), unless the format pins one with `locale()`.
 *
 *     NumberFormat::number()                       // 1 234,5
 *     NumberFormat::number(decimals: 2)            // 1 234,50
 *     NumberFormat::currency('PLN')                // 1 234,50 zł
 *     NumberFormat::percent(fraction: false)       // 45 -> 45%
 *     NumberFormat::percent(fraction: true)        // 0.45 -> 45%
 *     NumberFormat::compact()                      // 18 tys. / 18K
 *     NumberFormat::number()->unit('kg')           // 12,5 kg
 */
final readonly class NumberFormat
{
    /** ISO 4217 codes `fromUnit()` recognises as a currency (a three-letter unit such as `PCS` is not one). */
    private const array CURRENCIES = [
        'AED', 'AFN', 'ALL', 'AMD', 'ANG', 'AOA', 'ARS', 'AUD', 'AWG', 'AZN', 'BAM', 'BBD', 'BDT', 'BGN', 'BHD', 'BIF',
        'BMD', 'BND', 'BOB', 'BRL', 'BSD', 'BTN', 'BWP', 'BYN', 'BZD', 'CAD', 'CDF', 'CHF', 'CLP', 'CNY', 'COP', 'CRC',
        'CUP', 'CVE', 'CZK', 'DJF', 'DKK', 'DOP', 'DZD', 'EGP', 'ERN', 'ETB', 'EUR', 'FJD', 'FKP', 'GBP', 'GEL', 'GHS',
        'GIP', 'GMD', 'GNF', 'GTQ', 'GYD', 'HKD', 'HNL', 'HRK', 'HTG', 'HUF', 'IDR', 'ILS', 'INR', 'IQD', 'IRR', 'ISK',
        'JMD', 'JOD', 'JPY', 'KES', 'KGS', 'KHR', 'KMF', 'KPW', 'KRW', 'KWD', 'KYD', 'KZT', 'LAK', 'LBP', 'LKR', 'LRD',
        'LSL', 'LYD', 'MAD', 'MDL', 'MGA', 'MKD', 'MMK', 'MNT', 'MOP', 'MRU', 'MUR', 'MVR', 'MWK', 'MXN', 'MYR', 'MZN',
        'NAD', 'NGN', 'NIO', 'NOK', 'NPR', 'NZD', 'OMR', 'PAB', 'PEN', 'PGK', 'PHP', 'PKR', 'PLN', 'PYG', 'QAR', 'RON',
        'RSD', 'RUB', 'RWF', 'SAR', 'SBD', 'SCR', 'SDG', 'SEK', 'SGD', 'SHP', 'SLE', 'SOS', 'SRD', 'SSP', 'STN', 'SYP',
        'SZL', 'THB', 'TJS', 'TMT', 'TND', 'TOP', 'TRY', 'TTD', 'TWD', 'TZS', 'UAH', 'UGX', 'USD', 'UYU', 'UZS', 'VES',
        'VND', 'VUV', 'WST', 'XAF', 'XCD', 'XOF', 'XPF', 'YER', 'ZAR', 'ZMW', 'ZWL',
    ];

    private function __construct(
        private string $style = 'decimal',
        private ?string $currency = null,
        private bool $fraction = false,
        private ?int $decimals = null,
        private bool $compact = false,
        private ?string $unit = null,
        private ?string $locale = null,
    ) {}

    /**
     * A plain number with the locale's grouping and decimal separator. With no
     * `decimals` it shows up to two fraction digits (`1 234,5`); with `decimals`
     * it always shows exactly that many (`1 234,50`).
     */
    public static function number(?int $decimals = null): self
    {
        return new self(decimals: self::digits($decimals));
    }

    /**
     * An amount of money in an ISO 4217 currency (`PLN`, `EUR`, `USD`): `1 234,50 zł`
     * in Polish, `€1,234.50` in English. With no `decimals` the currency's own
     * number of digits is used (a whole tick on an axis drops them).
     */
    public static function currency(string $code, ?int $decimals = null): self
    {
        $code = strtoupper($code);

        if (preg_match('/^[A-Z]{3}$/', $code) !== 1) {
            throw new InvalidArgumentException("[{$code}] is not an ISO 4217 currency code.");
        }

        return new self('currency', $code, decimals: self::digits($decimals));
    }

    /**
     * A percentage. The scale is explicit, because both are common:
     * `fraction: false` — the value already is 0–100 (`45` is 45%);
     * `fraction: true` — the value is a 0–1 fraction (`0.45` is 45%).
     */
    public static function percent(bool $fraction, ?int $decimals = null): self
    {
        return new self('percent', fraction: $fraction, decimals: self::digits($decimals));
    }

    /** A number abbreviated for narrow spaces: `18 tys.` / `18K`, `1,2 mln` / `1.2M`. */
    public static function compact(?int $decimals = null): self
    {
        return new self(decimals: self::digits($decimals), compact: true);
    }

    /**
     * The format a free-text unit of a measure implies, or null for no unit: a
     * three-letter ISO 4217 code is a currency, `%` a percentage of 0–100, and
     * anything else (`pcs`, `kg`, `days`) a plain number followed by the unit.
     */
    public static function fromUnit(?string $unit): ?self
    {
        $unit = $unit === null ? '' : trim($unit);

        return match (true) {
            $unit === '' => null,
            $unit === '%' => self::percent(fraction: false),
            in_array($unit, self::CURRENCIES, true) => self::currency($unit),
            default => self::number()->unit($unit),
        };
    }

    /** The same format abbreviated (or, with false, not). Works on a currency too: `18 tys. zł`. */
    public function compacted(bool $compact = true): self
    {
        return $this->with(compact: $compact);
    }

    /** The same format followed by a plain suffix — `12,5 kg`, `3 szt.`, `7 dni` — for what Intl has no unit for. */
    public function unit(?string $unit): self
    {
        return $this->with(unit: $unit === '' ? null : $unit);
    }

    /** The same format pinned to a locale (`pl`, `en_GB`) instead of the application's. Null goes back to the application's. */
    public function locale(?string $locale): self
    {
        return $this->with(locale: $locale === '' ? null : $locale);
    }

    /** The same format with this many fraction digits (null: the default for its kind). */
    public function decimals(?int $decimals): self
    {
        return $this->with(decimals: self::digits($decimals));
    }

    public function equals(self $other): bool
    {
        return $this->toArray() === $other->toArray();
    }

    /**
     * The settings only, as they are stored on a measure.
     *
     * @return array{style: string, currency?: string, fraction?: bool, decimals?: int, compact?: bool, unit?: string, locale?: string}
     */
    public function toArray(): array
    {
        $format = ['style' => $this->style];

        if ($this->currency !== null) {
            $format['currency'] = $this->currency;
        }
        if ($this->style === 'percent') {
            $format['fraction'] = $this->fraction;
        }
        if ($this->decimals !== null) {
            $format['decimals'] = $this->decimals;
        }
        if ($this->compact) {
            $format['compact'] = true;
        }
        if ($this->unit !== null) {
            $format['unit'] = $this->unit;
        }
        if ($this->locale !== null) {
            $format['locale'] = $this->locale;
        }

        return $format;
    }

    /**
     * Rebuilds a format from `toArray()`.
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $style = $data['style'] ?? 'decimal';
        $decimals = isset($data['decimals']) && is_int($data['decimals']) ? $data['decimals'] : null;
        $unit = isset($data['unit']) && is_string($data['unit']) ? $data['unit'] : null;
        $locale = isset($data['locale']) && is_string($data['locale']) ? $data['locale'] : null;

        $format = match ($style) {
            'currency' => self::currency(is_string($data['currency'] ?? null) ? $data['currency'] : '', $decimals),
            'percent' => self::percent(($data['fraction'] ?? false) === true, $decimals),
            'decimal' => self::number($decimals),
            default => throw new InvalidArgumentException('A number format style must be one of [decimal, currency, percent].'),
        };

        return $format->compacted(($data['compact'] ?? false) === true)->unit($unit)->locale($locale);
    }

    /**
     * The marker the browser turns into a formatting function, for where it
     * sits in the option: `axis` (an axis label), `value` (a tooltip's value)
     * or `label` (a data label). The locale is resolved here, at render time,
     * so every option the server builds is consistent with the page's locale.
     *
     * @param  'axis'|'value'|'label'  $role
     * @return array{__number__: array<string, mixed>}
     */
    public function marker(string $role): array
    {
        return ['__number__' => [...$this->toArray(), 'locale' => $this->resolvedLocale(), 'role' => $role]];
    }

    /** The BCP 47 tag the format is written for: the pinned locale or the application's. */
    public function resolvedLocale(): string
    {
        return str_replace('_', '-', $this->locale ?? app()->getLocale());
    }

    private static function digits(?int $decimals): ?int
    {
        if ($decimals !== null && ($decimals < 0 || $decimals > 20)) {
            throw new InvalidArgumentException('Decimals must be between 0 and 20.');
        }

        return $decimals;
    }

    private function with(mixed ...$changes): self
    {
        $current = [
            'style' => $this->style,
            'currency' => $this->currency,
            'fraction' => $this->fraction,
            'decimals' => $this->decimals,
            'compact' => $this->compact,
            'unit' => $this->unit,
            'locale' => $this->locale,
        ];

        return new self(...array_merge($current, $changes));
    }
}
