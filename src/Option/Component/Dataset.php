<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Illuminate\Support\Traits\Conditionable;

final class Dataset implements Node
{
    use Conditionable;
    use HasRaw;

    private ?string $id = null;

    /** @var array<int, mixed>|null */
    private ?array $source = null;

    /** @var list<string|array<string, mixed>>|null */
    private ?array $dimensions = null;

    private ?bool $sourceHeader = null;

    private ?int $fromDatasetIndex = null;

    private ?string $fromDatasetId = null;

    /** @var array<string, mixed>|list<array<string, mixed>>|null */
    private ?array $transform = null;

    private ?int $fromTransformResult = null;

    public static function make(): self
    {
        return new self;
    }

    /**
     * Build a dataset from an Eloquent query result (or any iterable of
     * models/rows), projecting each row to just the chart `$columns` and
     * naming them as the dataset `dimensions`. Unlike handing a
     * `Collection<Model>` to a series' `data()` — where each model would
     * balloon to its full attribute bag — this keeps only the columns the
     * chart needs, giving the `[{col: value}, …]` row shape that a series'
     * `encode(x: 'col', y: 'col')` reads.
     *
     * ```php
     * Dataset::fromModels(Order::query()->get(), ['created_at', 'total']);
     * ```
     *
     * Values are pulled with `data_get()`, so a model attribute, an array key,
     * or an object property all resolve — and a dotted path works too
     * (`'customer.name'`).
     *
     * @param  iterable<mixed>  $rows
     * @param  list<string>  $columns
     */
    public static function fromModels(iterable $rows, array $columns): self
    {
        $source = [];
        foreach ($rows as $row) {
            $projected = [];
            foreach ($columns as $column) {
                $projected[$column] = data_get($row, $column);
            }
            $source[] = $projected;
        }

        return self::make()->source($source)->dimensions($columns);
    }

    /**
     * The dataset's rows: a 2D array (`[['Product', 'Sales'], ['Cookies', 321]]`)
     * or a list of objects (`[['Product' => 'Cookies', 'Sales' => 321]]`).
     *
     * Accepts any `iterable`, so an Eloquent `Collection` of rows/models feeds
     * in directly — each row's outer key is dropped (rows are positional) and a
     * model is unwrapped to its attribute array, which is exactly the row shape
     * `dataset.source` + `encode()` expect.
     *
     * @param  iterable<mixed>  $source
     */
    public function source(iterable $source): self
    {
        $this->source = Normalize::value(Normalize::list($source));

        return $this;
    }

    /**
     * Names the dataset's dimensions, e.g. `['Product', 'Sales']`, or a richer
     * per-dimension definition (`['name' => 'Sales', 'type' => 'float']`).
     *
     * @param  list<string|array<string, mixed>>  $dimensions
     */
    public function dimensions(array $dimensions): self
    {
        $this->dimensions = $dimensions;

        return $this;
    }

    /** Whether the first row/column of `source` is a header naming the dimensions. */
    public function sourceHeader(bool $header = true): self
    {
        $this->sourceHeader = $header;

        return $this;
    }

    /** Derives this dataset from another dataset's output, by its index in `Option::dataset()`. */
    public function fromDatasetIndex(int $index): self
    {
        $this->fromDatasetIndex = $index;

        return $this;
    }

    /** Derives this dataset from another dataset's output, by its `id()`. */
    public function fromDatasetId(string $id): self
    {
        $this->fromDatasetId = $id;

        return $this;
    }

    /**
     * Applies a transform (e.g. `Transform::filter(...)`/`Transform::sort(...)`)
     * to the upstream source, or a raw pipe of transforms as a plain array.
     *
     * @param  Transform|array<string, mixed>  $transform
     */
    public function transform(Transform | array $transform): self
    {
        $this->transform = Normalize::arr($transform);

        return $this;
    }

    /** Names this dataset so other datasets/series can refer to it via `fromDatasetId()`. */
    public function id(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * When the upstream `transform()` produces multiple result sets (e.g. a
     * multi-output transform), picks which one this dataset uses, by index.
     */
    public function fromTransformResult(int $index): self
    {
        $this->fromTransformResult = $index;

        return $this;
    }

    public function toArray(): array
    {
        return $this->mergeRaw(Normalize::filled([
            'id' => $this->id,
            'source' => $this->source,
            'dimensions' => $this->dimensions,
            'sourceHeader' => $this->sourceHeader,
            'fromDatasetIndex' => $this->fromDatasetIndex,
            'fromDatasetId' => $this->fromDatasetId,
            'transform' => $this->transform,
            'fromTransformResult' => $this->fromTransformResult,
        ]));
    }
}
