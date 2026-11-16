<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Data;

use Happenv\FilamentEnhancedCharts\Enums\Additivity;
use Happenv\FilamentEnhancedCharts\Enums\DimensionKind;
use Happenv\FilamentEnhancedCharts\Enums\MeasureRole;

/**
 * The form of a `ChartDataset` — how many dimensions (and of which kinds) and
 * how many measures, which `MeasureRole`s they carry, and which dimensions are
 * multi-valued, and how each measure adds up — without its rows. A
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
     * The positions (in `$dimensions`) of the multi-valued dimensions, ascending.
     *
     * @var list<int>
     */
    public array $multiValued;

    /**
     * How each measure adds up, one entry per measure in order (`Additive` for a measure not named).
     *
     * @var list<Additivity>
     */
    public array $additivity;

    /**
     * @param  list<DimensionKind>  $dimensions
     * @param  list<MeasureRole>  $roles
     * @param  list<int>  $multiValued  positions of the multi-valued dimensions; one outside `$dimensions` is ignored
     * @param  list<Additivity>  $additivity  the additivity of the measures by position; missing ones are `Additive`, extra ones are ignored
     */
    public function __construct(array $dimensions, public int $measures, array $roles = [], array $multiValued = [], array $additivity = [])
    {
        $additivity = array_values($additivity);
        $this->additivity = array_map(
            static fn (int $index): Additivity => $additivity[$index] ?? Additivity::Additive,
            array_keys(array_fill(0, max($measures, 0), null)),
        );

        $this->dimensions = array_values($dimensions);
        $this->multiValued = array_values(array_filter(
            array_keys($this->dimensions),
            static fn (int $index): bool => in_array($index, $multiValued, true),
        ));
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
        return new self($this->dimensions, $this->measures, array_values($roles), $this->multiValued, $this->additivity);
    }

    /** The same shape whose dimensions at these positions are multi-valued (and no others). */
    public function withMultiValued(int ...$indexes): self
    {
        return new self($this->dimensions, $this->measures, $this->roles, array_values($indexes), $this->additivity);
    }

    /** Whether the dimension at this position is multi-valued: its rows count an entity once per value. */
    public function isMultiValuedAt(int $index): bool
    {
        return in_array($index, $this->multiValued, true);
    }

    public function hasMultiValuedDimension(): bool
    {
        return $this->multiValued !== [];
    }

    /** The same shape whose measures add up as given, by position (`Additive` for those not given). */
    public function withAdditivity(Additivity ...$additivity): self
    {
        return new self($this->dimensions, $this->measures, $this->roles, $this->multiValued, array_values($additivity));
    }

    /** How the measure at this position adds up (`Additive` outside the measures). */
    public function additivityAt(int $index): Additivity
    {
        return $this->additivity[$index] ?? Additivity::Additive;
    }

    public function hasNonAdditiveMeasure(): bool
    {
        return array_any($this->additivity, static fn (Additivity $additivity): bool => $additivity !== Additivity::Additive);
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

    /** E.g. `2 dimensions (category, time) and 1 measure`, `1 dimension (category (multi-valued)) and 1 measure`, or `1 dimension (time) and 4 measures (roles: open, close, low, high)`. */
    public function describe(): string
    {
        $dimensions = $this->dimensions === []
            ? 'no dimensions'
            : sprintf(
                '%d %s (%s)',
                count($this->dimensions),
                count($this->dimensions) === 1 ? 'dimension' : 'dimensions',
                implode(', ', array_map(
                    fn (DimensionKind $kind, int $index): string => $kind->value . ($this->isMultiValuedAt($index) ? ' (multi-valued)' : ''),
                    $this->dimensions,
                    array_keys($this->dimensions),
                )),
            );

        $notes = [];
        if ($this->roles !== []) {
            $notes[] = 'roles: ' . implode(', ', array_map(static fn (MeasureRole $role): string => $role->value, $this->roles));
        }

        foreach ([Additivity::NonAdditive, Additivity::SemiAdditive] as $kind) {
            $count = count(array_filter($this->additivity, static fn (Additivity $additivity): bool => $additivity === $kind));
            if ($count > 0) {
                $notes[] = sprintf('%d %s', $count, $kind->value);
            }
        }

        return sprintf(
            '%s and %d %s%s',
            $dimensions,
            $this->measures,
            $this->measures === 1 ? 'measure' : 'measures',
            $notes === [] ? '' : ' (' . implode('; ', $notes) . ')',
        );
    }

    /**
     * `multiValued` (the positions of the multi-valued dimensions) is present only when there is one, and
     * `additivity` (one value per measure) only when a measure is not additive.
     *
     * @return array{dimensions: list<string>, measures: int, roles: list<string>, multiValued?: list<int>, additivity?: list<string>}
     */
    public function toArray(): array
    {
        $shape = [
            'dimensions' => array_map(static fn (DimensionKind $kind): string => $kind->value, $this->dimensions),
            'measures' => $this->measures,
            'roles' => array_map(static fn (MeasureRole $role): string => $role->value, $this->roles),
        ];

        if ($this->multiValued !== []) {
            $shape['multiValued'] = $this->multiValued;
        }

        if ($this->hasNonAdditiveMeasure()) {
            $shape['additivity'] = array_map(static fn (Additivity $additivity): string => $additivity->value, $this->additivity);
        }

        return $shape;
    }
}
