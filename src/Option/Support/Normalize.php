<?php

namespace Happenv\FilamentEnhancedCharts\Option\Support;

use BcMath\Number;
use Filament\Support\Colors\Color;
use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Illuminate\Contracts\Support\Arrayable;

final class Normalize
{
    /**
     * Normalize any value destined for the ECharts option tree.
     *
     * `\BcMath\Number` and Filament `RawJs` become `{__js__: '…'}` markers that
     * the client-side reviver turns back into an exact number / a real function.
     */
    public static function value(mixed $value): mixed
    {
        if ($value instanceof Number) {
            return ['__js__' => self::trimNumber((string) $value)];
        }

        if ($value instanceof RawJs) {
            return ['__js__' => (string) $value];
        }

        if ($value instanceof Node || $value instanceof Arrayable) {
            return self::value($value->toArray());
        }

        if (is_array($value)) {
            return array_map(self::value(...), $value);
        }

        return $value;
    }

    /**
     * Build a `{__js__}` marker from a JS value. A bare string is treated as a
     * raw JS expression — the same convenience `RawJs::make()` gives.
     *
     * @return array{__js__: string}
     */
    public static function js(RawJs | string $value): array
    {
        return ['__js__' => $value instanceof RawJs ? (string) $value : $value];
    }

    /**
     * A formatter value: a bare string is a literal ECharts template ('{value}',
     * '{b}: {c}') and passes through unchanged; a RawJs is executable JS emitted
     * as a `{__js__}` marker for the client reviver.
     *
     * @return string|array{__js__: string}
     */
    public static function formatter(RawJs | string $value): string | array
    {
        return $value instanceof RawJs ? ['__js__' => (string) $value] : $value;
    }

    /**
     * Coerce a style value to an array so setters can accept either a builder
     * (ItemStyle/LineStyle/Label/Emphasis, etc.) or a plain array.
     *
     * @param  Node|array<string, mixed>  $value
     * @return array<string, mixed>
     */
    public static function arr(Node | array $value): array
    {
        return $value instanceof Node ? $value->toArray() : $value;
    }

    /**
     * Resolve a color to a value ECharts understands. A Filament color palette
     * (`Filament\Support\Colors\Color::Amber`, a shade-keyed array) collapses to
     * its base 500 shade. (Pass e.g. `Color::Amber[600]` for another shade.)
     *
     * Filament v4 palettes are `oklch(…)` strings. ECharts' canvas renderer
     * paints an `oklch()` fill, but its own color parser (zrender) can't read
     * `oklch()` — so when it computes an emphasis/hover shade by lightening the
     * base color it produces an invalid color and the element renders empty
     * (bars vanish on hover). We hand any `oklch()` string to Filament's own
     * `Color::convertToRgb()` so ECharts can both paint AND derive shades from
     * it. Only `oklch()` is converted — a hex/rgb string, a keyword such as
     * `'gradient'`, or an ECharts gradient object passes through untouched
     * (those Filament would otherwise mangle into `rgb(gradient)`).
     *
     * @param  string|array<mixed>  $color
     * @return string|array<mixed>
     */
    public static function color(string | array $color): string | array
    {
        $resolved = is_array($color) && isset($color[500]) ? $color[500] : $color;

        if (is_string($resolved) && str_starts_with($resolved, 'oklch(')) {
            return Color::convertToRgb($resolved);
        }

        return $resolved;
    }

    /**
     * Unwrap a string-backed enum to its value; a bare string passes through.
     * The standard shape for every `Enum|string` setter in the option tree.
     */
    public static function enum(\BackedEnum | string $value): string
    {
        return $value instanceof \BackedEnum ? (string) $value->value : $value;
    }

    /**
     * Materialize any iterable to a plain array, KEYS PRESERVED. Use for
     * associative data (rows/objects); use `list()` for a positional series.
     *
     * @param  iterable<mixed>  $values
     * @return array<mixed>
     */
    public static function iterable(iterable $values): array
    {
        return is_array($values) ? $values : iterator_to_array($values);
    }

    /**
     * Materialize any iterable to a 0-indexed list, DROPPING KEYS. This is the
     * right shape for a series/axis `data` list: ECharts reads `data` as a
     * positional array, so a keyed Collection (`pluck('total', 'month')`, a
     * `groupBy`/`keyBy` result) would otherwise serialize to a JSON object and
     * render nothing.
     *
     * @param  iterable<mixed>  $values
     * @return list<mixed>
     */
    public static function list(iterable $values): array
    {
        return array_values(self::iterable($values));
    }

    /**
     * ECharts' single-vs-list convention for component slots: one element
     * emits as the bare object, several as the list.
     *
     * @param  non-empty-list<mixed>  $items
     */
    public static function oneOrList(array $items): mixed
    {
        return count($items) === 1 ? $items[0] : $items;
    }

    /**
     * Drop the null entries from an ordered `key => value` map, keeping order
     * and preserving `false`/`0`/`[]`. The one-line form of the "emit a key
     * only when it was set" rule that a builder's `toArray()`/`build()` spells
     * out as an `if ($this->x !== null)` chain.
     *
     * @param  array<string, mixed>  $map
     * @return array<string, mixed>
     */
    public static function filled(array $map): array
    {
        return array_filter($map, static fn (mixed $value): bool => $value !== null);
    }

    /**
     * The exact decimal literal for a BcMath number: trailing zeros and a
     * trailing dot removed (lossless through PHP; the browser parses to float64).
     */
    private static function trimNumber(string $decimal): string
    {
        if (! str_contains($decimal, '.')) {
            return $decimal;
        }

        $trimmed = rtrim(rtrim($decimal, '0'), '.');

        return $trimmed === '' || $trimmed === '-' ? '0' : $trimmed;
    }
}
