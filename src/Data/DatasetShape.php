<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Data;

use Happenv\FilamentEnhancedCharts\Enums\DimensionKind;

/**
 * The form of a `ChartDataset` — how many dimensions (and of which kinds) and
 * how many measures — without its rows. A preset decides whether it can draw a
 * dataset from the shape alone.
 */
final readonly class DatasetShape
{
    /** @var list<DimensionKind> */
    public array $dimensions;

    /** @param  list<DimensionKind>  $dimensions */
    public function __construct(array $dimensions, public int $measures)
    {
        $this->dimensions = array_values($dimensions);
    }

    public static function of(int $measures, DimensionKind ...$dimensions): self
    {
        return new self($dimensions, $measures);
    }

    public function dimensionCount(): int
    {
        return count($this->dimensions);
    }

    public function kindAt(int $index): ?DimensionKind
    {
        return $this->dimensions[$index] ?? null;
    }

    public function hasKind(DimensionKind $kind): bool
    {
        return in_array($kind, $this->dimensions, true);
    }

    /** E.g. `2 dimensions (category, time) and 1 measure`. */
    public function describe(): string
    {
        $dimensions = $this->dimensions === []
            ? 'no dimensions'
            : sprintf(
                '%d %s (%s)',
                count($this->dimensions),
                count($this->dimensions) === 1 ? 'dimension' : 'dimensions',
                implode(', ', array_map(static fn (DimensionKind $kind): string => $kind->value, $this->dimensions)),
            );

        return sprintf('%s and %d %s', $dimensions, $this->measures, $this->measures === 1 ? 'measure' : 'measures');
    }

    /** @return array{dimensions: list<string>, measures: int} */
    public function toArray(): array
    {
        return [
            'dimensions' => array_map(static fn (DimensionKind $kind): string => $kind->value, $this->dimensions),
            'measures' => $this->measures,
        ];
    }
}
