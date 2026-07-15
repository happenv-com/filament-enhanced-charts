<?php

namespace Happenv\FilamentEnhancedCharts\Data;

use Happenv\FilamentEnhancedCharts\Option\DataPoint;

/**
 * A small, dependency-free bridge from Laravel data shapes to the labels /
 * values / `DataPoint`s a series and a category axis consume.
 *
 * It reads plain shapes (a keyed map, or rows with a label/value column), so
 * it is decoupled from any query package: you can feed it a keyed `pluck`, a
 * hand-built collection, or the `Collection<TrendValue>` that
 * `flowframe/laravel-trend` produces — this class never constructs a query
 * itself, it only reshapes a result.
 *
 * ```php
 * $data = ChartData::fromTimeSeries(
 *     Trend::model(Order::class)->between($start, now())->perDay()->sum('total'),
 * );
 *
 * Option::cartesian()
 *     ->xAxis(CategoryAxis::make()->data($data->labels()))
 *     ->series(LineSeries::make()->data($data->values()));
 * ```
 */
final readonly class ChartData
{
    /**
     * @param  list<mixed>  $labels
     * @param  list<mixed>  $values
     */
    private function __construct(
        private array $labels,
        private array $values,
    ) {}

    /**
     * From a keyed map/Collection whose KEY is the label and value is the
     * measure — the shape of a keyed aggregation:
     * `Order::query()->groupBy('channel')->pluck('total', 'channel')`, or
     * `['B2B' => 60, 'B2C' => 40]`.
     *
     * @param  iterable<int|string, mixed>  $pairs
     */
    public static function fromPairs(iterable $pairs): self
    {
        $labels = [];
        $values = [];

        foreach ($pairs as $label => $value) {
            $labels[] = $label;
            $values[] = $value;
        }

        return new self($labels, $values);
    }

    /**
     * From a list of rows each carrying a label column and a value column —
     * the shape of a time-series aggregation, e.g. `flowframe/laravel-trend`'s
     * `Collection<TrendValue{date, aggregate}>`. Values are read with
     * `data_get()`, so array rows, objects, and models all resolve.
     *
     * @param  iterable<mixed>  $rows
     */
    public static function fromTimeSeries(iterable $rows, string $labelKey = 'date', string $valueKey = 'aggregate'): self
    {
        $labels = [];
        $values = [];

        foreach ($rows as $row) {
            $labels[] = data_get($row, $labelKey);
            $values[] = data_get($row, $valueKey);
        }

        return new self($labels, $values);
    }

    /**
     * The labels, for a `CategoryAxis::make()->data(...)`.
     *
     * @return list<mixed>
     */
    public function labels(): array
    {
        return $this->labels;
    }

    /**
     * The measures, for a `LineSeries`/`BarSeries` `->data(...)`.
     *
     * @return list<mixed>
     */
    public function values(): array
    {
        return $this->values;
    }

    /**
     * The measures as `DataPoint`s named by their label — for a named series
     * (pie, funnel): `->series(PieSeries::make()->data($data->toDataPoints()))`.
     *
     * @return list<DataPoint>
     */
    public function toDataPoints(): array
    {
        $points = [];

        foreach ($this->values as $index => $value) {
            $point = DataPoint::make($value);
            $label = $this->labels[$index] ?? null;

            $points[] = $label !== null ? $point->name((string) $label) : $point;
        }

        return $points;
    }
}
