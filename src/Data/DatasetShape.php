<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Data;

use Happenv\FilamentEnhancedCharts\Enums\DimensionKind;
use Happenv\FilamentEnhancedCharts\Enums\MeasureRole;

/**
 * The form of a `ChartDataset` — how many dimensions (and of which kinds) and
 * how many measures, and which `MeasureRole`s they carry — without its rows. A
 * preset decides whether it can draw a dataset from the shape alone.
 */
final readonly class DatasetShape
{
    /** @var list<DimensionKind> */
    public array $dimensions;

    /**
     * The roles the measures carry, each once, in `MeasureRole` declaration order.
     *
     * @var list<MeasureRole>
     */
    public array $roles;

    /**
     * @param  list<DimensionKind>  $dimensions
     * @param  list<MeasureRole>  $roles
     */
    public function __construct(array $dimensions, public int $measures, array $roles = [])
    {
        $this->dimensions = array_values($dimensions);
        $this->roles = array_values(array_filter(
            MeasureRole::cases(),
            static fn (MeasureRole $role): bool => in_array($role, $roles, true),
        ));
    }

    public static function of(int $measures, DimensionKind ...$dimensions): self
    {
        return new self($dimensions, $measures);
    }

    /** The same shape whose measures carry exactly these roles. */
    public function withRoles(MeasureRole ...$roles): self
    {
        return new self($this->dimensions, $this->measures, array_values($roles));
    }

    public function hasRole(MeasureRole $role): bool
    {
        return in_array($role, $this->roles, true);
    }

    /** Whether the measures carry exactly these roles — no more, no fewer, in any order. */
    public function hasExactlyRoles(MeasureRole ...$roles): bool
    {
        return $this->roles === array_values(array_filter(
            MeasureRole::cases(),
            static fn (MeasureRole $role): bool => in_array($role, $roles, true),
        ));
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

    /** E.g. `2 dimensions (category, time) and 1 measure`, or `1 dimension (time) and 4 measures (roles: open, close, low, high)`. */
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

        $notes = [];
        if ($this->roles !== []) {
            $notes[] = 'roles: ' . implode(', ', array_map(static fn (MeasureRole $role): string => $role->value, $this->roles));
        }

        return sprintf(
            '%s and %d %s%s',
            $dimensions,
            $this->measures,
            $this->measures === 1 ? 'measure' : 'measures',
            $notes === [] ? '' : ' (' . implode('; ', $notes) . ')',
        );
    }

    /** @return array{dimensions: list<string>, measures: int, roles: list<string>} */
    public function toArray(): array
    {
        return [
            'dimensions' => array_map(static fn (DimensionKind $kind): string => $kind->value, $this->dimensions),
            'measures' => $this->measures,
            'roles' => array_map(static fn (MeasureRole $role): string => $role->value, $this->roles),
        ];
    }
}
