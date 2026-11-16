<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Data;

use BcMath\Number;
use Happenv\FilamentEnhancedCharts\Enums\DimensionKind;
use Happenv\FilamentEnhancedCharts\Exceptions\InvalidChartDataset;
use Stringable;

/**
 * A tabular, already-aggregated dataset: rows split by `dimensions` (strings —
 * channel, month, …) carrying `measures` (numbers — orders, revenue, …). It is
 * the only input a chart preset needs, so a consumer can draw "orders per
 * channel per month" without knowing which chart types exist.
 *
 * ```php
 * $dataset = ChartDataset::fromRows(
 *     [DatasetDimension::time('month', 'Month'), DatasetDimension::category('channel', 'Channel')],
 *     [new DatasetMeasure('orders', 'Orders')],
 *     [['month' => 'Jan', 'channel' => 'Allegro', 'orders' => 12], …],
 * );
 *
 * $preset = app(ChartPresets::class)->accepting($dataset->shape())[0];
 * $option = $preset->option($dataset);
 * ```
 *
 * The constructor and `fromArray()` are strict (consumers persist the dataset
 * as JSON and must get the same value back); `fromRows()` is the lenient entry
 * point for query results (numeric strings, ints as labels).
 *
 * @phpstan-type Row array<string, string|int|float|null>
 * @phpstan-type Series array{name: string, values: list<int|float|null>}
 */
final readonly class ChartDataset
{
    /** The label standing in for a `null` dimension value. */
    public const string NULL_LABEL = '—';

    private const string NAME_PATTERN = '/^[A-Za-z_][A-Za-z0-9_\-]*$/';

    /** @var list<DatasetDimension> */
    public array $dimensions;

    /** @var list<DatasetMeasure> */
    public array $measures;

    /** @var list<Row> */
    public array $rows;

    /**
     * @param  list<DatasetDimension>  $dimensions
     * @param  list<DatasetMeasure>  $measures
     * @param  list<Row>  $rows  keyed by dimension/measure name; dimension values `string|null`, measure values `int|float|null`
     */
    public function __construct(array $dimensions, array $measures, array $rows)
    {
        $dimensions = array_values($dimensions);
        $measures = array_values($measures);

        if ($measures === []) {
            throw InvalidChartDataset::at('measures', 'at least one measure is required');
        }

        $names = [];
        foreach ([...$dimensions, ...$measures] as $column) {
            if (isset($names[$column->name])) {
                throw InvalidChartDataset::at('dimensions|measures', "the name [{$column->name}] is used twice");
            }
            $names[$column->name] = true;
        }

        $this->dimensions = $dimensions;
        $this->measures = $measures;
        $this->rows = $this->validatedRows(array_values($rows));
    }

    /**
     * Lenient construction for query results: numeric strings (a `DECIMAL` from
     * the database) and `BcMath\Number` become `int|float`; ints/`Stringable`s
     * become label strings.
     *
     * @param  list<DatasetDimension>  $dimensions
     * @param  list<DatasetMeasure>  $measures
     * @param  iterable<mixed>  $rows  rows as arrays or objects (read with `data_get()`)
     */
    public static function fromRows(array $dimensions, array $measures, iterable $rows): self
    {
        $coerced = [];

        foreach ($rows as $index => $row) {
            $item = [];

            foreach ($dimensions as $dimension) {
                $value = data_get($row, $dimension->name);
                $item[$dimension->name] = match (true) {
                    $value === null, is_string($value) => $value,
                    is_int($value), is_float($value), $value instanceof Stringable => (string) $value,
                    default => throw InvalidChartDataset::at("rows[{$index}].{$dimension->name}", 'a dimension value must be a string or null'),
                };
            }

            foreach ($measures as $measure) {
                $value = data_get($row, $measure->name);

                if ($value instanceof Number) {
                    $value = (string) $value;
                }

                $item[$measure->name] = is_string($value) && is_numeric($value) ? $value + 0 : $value;
            }

            $coerced[] = $item;
        }

        return new self($dimensions, $measures, $coerced);
    }

    /**
     * Strict inverse of `toArray()`: unknown keys, wrong types and missing
     * fields throw `InvalidChartDataset` instead of being repaired.
     *
     * @param  array<mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        self::assertKeys($data, ['dimensions', 'measures', 'rows'], '$');

        $dimensions = [];
        foreach (self::listAt($data, 'dimensions') as $index => $item) {
            $path = "dimensions[{$index}]";
            $item = self::arrayAt($item, $path);
            self::assertKeys($item, ['name', 'label', 'kind'], $path);

            $kind = is_string($item['kind']) ? DimensionKind::tryFrom($item['kind']) : null;
            if (! $kind instanceof DimensionKind) {
                throw InvalidChartDataset::at("{$path}.kind", 'must be one of [category, time]');
            }

            $dimensions[] = new DatasetDimension(
                self::stringAt($item, 'name', $path),
                self::stringAt($item, 'label', $path),
                $kind,
            );
        }

        $measures = [];
        foreach (self::listAt($data, 'measures') as $index => $item) {
            $path = "measures[{$index}]";
            $item = self::arrayAt($item, $path);
            self::assertKeys($item, ['name', 'label'], $path);

            $measures[] = new DatasetMeasure(
                self::stringAt($item, 'name', $path),
                self::stringAt($item, 'label', $path),
            );
        }

        $rows = [];
        foreach (self::listAt($data, 'rows') as $index => $row) {
            $rows[] = self::arrayAt($row, "rows[{$index}]");
        }

        /** @var list<Row> $rows */
        return new self($dimensions, $measures, $rows);
    }

    /**
     * @return array{
     *     dimensions: list<array{name: string, label: string, kind: string}>,
     *     measures: list<array{name: string, label: string}>,
     *     rows: list<Row>,
     * }
     */
    public function toArray(): array
    {
        return [
            'dimensions' => array_map(static fn (DatasetDimension $dimension): array => $dimension->toArray(), $this->dimensions),
            'measures' => array_map(static fn (DatasetMeasure $measure): array => $measure->toArray(), $this->measures),
            'rows' => $this->rows,
        ];
    }

    public function shape(): DatasetShape
    {
        return new DatasetShape(
            array_map(static fn (DatasetDimension $dimension): DimensionKind => $dimension->kind, $this->dimensions),
            count($this->measures),
        );
    }

    public function isEmpty(): bool
    {
        return $this->rows === [];
    }

    public function dimension(string $name): DatasetDimension
    {
        foreach ($this->dimensions as $dimension) {
            if ($dimension->name === $name) {
                return $dimension;
            }
        }

        throw InvalidChartDataset::at('dimensions', "there is no dimension [{$name}]");
    }

    public function measure(string $name): DatasetMeasure
    {
        foreach ($this->measures as $measure) {
            if ($measure->name === $name) {
                return $measure;
            }
        }

        throw InvalidChartDataset::at('measures', "there is no measure [{$name}]");
    }

    /**
     * The label of a dimension in every row, in row order (`NULL_LABEL` for a null).
     *
     * @return list<string>
     */
    public function labels(string $dimension): array
    {
        $this->dimension($dimension);

        return array_map(
            static fn (array $row): string => self::labelOf($row[$dimension]),
            $this->rows,
        );
    }

    /**
     * The distinct labels of a dimension, in order of first appearance.
     *
     * @return list<string>
     */
    public function distinct(string $dimension): array
    {
        return array_values(array_unique($this->labels($dimension)));
    }

    /**
     * A measure's value in every row, in row order.
     *
     * @return list<int|float|null>
     */
    public function values(string $measure): array
    {
        $this->measure($measure);

        return array_map(
            static fn (array $row): int | float | null => self::numberOf($row[$measure]),
            $this->rows,
        );
    }

    /**
     * A measure summed per distinct label of a dimension, in order of first
     * appearance (`null` when every contributing value is null).
     *
     * @return list<array{name: string, value: int|float|null}>
     */
    public function totals(string $dimension, string $measure): array
    {
        $labels = $this->labels($dimension);
        $values = $this->values($measure);

        $totals = [];
        $order = [];
        foreach ($labels as $index => $label) {
            if (! array_key_exists($label, $totals)) {
                $totals[$label] = null;
                $order[] = $label;
            }

            $value = $values[$index];
            if ($value !== null) {
                $totals[$label] = ($totals[$label] ?? 0) + $value;
            }
        }

        return array_map(
            static fn (string $label): array => ['name' => $label, 'value' => $totals[$label]],
            $order,
        );
    }

    /**
     * One series per distinct label of `$seriesDimension`, each carrying the
     * measure summed per distinct label of `$xDimension` — values aligned with
     * `distinct($xDimension)`, `null` where a combination has no row.
     *
     * @return list<Series>
     */
    public function breakdown(string $xDimension, string $seriesDimension, string $measure): array
    {
        $x = $this->labels($xDimension);
        $series = $this->labels($seriesDimension);
        $values = $this->values($measure);

        $categories = array_values(array_unique($x));
        $names = array_values(array_unique($series));

        $cells = [];
        foreach ($x as $index => $category) {
            $value = $values[$index];
            if ($value === null) {
                continue;
            }

            $cells[$series[$index]][$category] = ($cells[$series[$index]][$category] ?? 0) + $value;
        }

        return array_map(
            static fn (string $name): array => [
                'name' => $name,
                'values' => array_map(static fn (string $category): int | float | null => $cells[$name][$category] ?? null, $categories),
            ],
            $names,
        );
    }

    /** The label for a dimension value (`NULL_LABEL` for null). */
    public static function labelOf(?string $value): string
    {
        return $value ?? self::NULL_LABEL;
    }

    /** @internal Shared by `DatasetDimension` / `DatasetMeasure`. */
    public static function assertValidName(string $name, string $what): void
    {
        if (preg_match(self::NAME_PATTERN, $name) !== 1) {
            throw InvalidChartDataset::at($what . 's', "the {$what} name [{$name}] must start with a letter or underscore and contain only letters, digits, [_] and [-]");
        }
    }

    private static function numberOf(string | int | float | null $value): int | float | null
    {
        return is_string($value) ? null : $value;
    }

    /**
     * @param  list<mixed>  $rows
     * @return list<Row>
     */
    private function validatedRows(array $rows): array
    {
        $expected = [
            ...array_map(static fn (DatasetDimension $dimension): string => $dimension->name, $this->dimensions),
            ...array_map(static fn (DatasetMeasure $measure): string => $measure->name, $this->measures),
        ];
        $dimensionNames = array_map(static fn (DatasetDimension $dimension): string => $dimension->name, $this->dimensions);

        $validated = [];
        foreach ($rows as $index => $row) {
            $path = "rows[{$index}]";

            if (! is_array($row)) {
                throw InvalidChartDataset::at($path, 'a row must be an array');
            }

            $keys = array_map(strval(...), array_keys($row));
            $missing = array_diff($expected, $keys);
            $unknown = array_diff($keys, $expected);
            if ($missing !== [] || $unknown !== []) {
                throw InvalidChartDataset::at($path, sprintf(
                    'a row must have exactly the keys [%s]%s%s',
                    implode(', ', $expected),
                    $missing !== [] ? '; missing [' . implode(', ', $missing) . ']' : '',
                    $unknown !== [] ? '; unknown [' . implode(', ', $unknown) . ']' : '',
                ));
            }

            $item = [];
            foreach ($expected as $name) {
                $value = $row[$name];

                if (in_array($name, $dimensionNames, true)) {
                    if ($value !== null && ! is_string($value)) {
                        throw InvalidChartDataset::at("{$path}.{$name}", 'a dimension value must be a string or null');
                    }
                } elseif ($value !== null && ! is_int($value) && (! is_float($value) || ! is_finite($value))) {
                    throw InvalidChartDataset::at("{$path}.{$name}", 'a measure value must be a finite int, float or null');
                }

                $item[$name] = $value;
            }

            $validated[] = $item;
        }

        return $validated;
    }

    /**
     * @param  array<mixed>  $data
     * @param  list<string>  $keys
     */
    private static function assertKeys(array $data, array $keys, string $path): void
    {
        $actual = array_map(strval(...), array_keys($data));
        $missing = array_diff($keys, $actual);
        $unknown = array_diff($actual, $keys);

        if ($missing !== [] || $unknown !== []) {
            throw InvalidChartDataset::at($path, sprintf(
                'expected exactly the keys [%s]%s%s',
                implode(', ', $keys),
                $missing !== [] ? '; missing [' . implode(', ', $missing) . ']' : '',
                $unknown !== [] ? '; unknown [' . implode(', ', $unknown) . ']' : '',
            ));
        }
    }

    /**
     * @param  array<mixed>  $data
     * @return list<mixed>
     */
    private static function listAt(array $data, string $key): array
    {
        $value = $data[$key];

        if (! is_array($value) || ! array_is_list($value)) {
            throw InvalidChartDataset::at($key, 'must be a list');
        }

        return $value;
    }

    /** @return array<mixed> */
    private static function arrayAt(mixed $value, string $path): array
    {
        if (! is_array($value)) {
            throw InvalidChartDataset::at($path, 'must be an object');
        }

        return $value;
    }

    /** @param  array<mixed>  $data */
    private static function stringAt(array $data, string $key, string $path): string
    {
        $value = $data[$key];

        if (! is_string($value)) {
            throw InvalidChartDataset::at("{$path}.{$key}", 'must be a string');
        }

        return $value;
    }
}
