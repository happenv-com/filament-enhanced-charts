<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Enums;

/**
 * Whether the values of a `ChartDataset` measure can be added up into a whole.
 *
 * `Additive` (the default) sums over every dimension: orders, revenue, units.
 * `NonAdditive` never does: a ratio (margin %), an average, a distinct count.
 * `SemiAdditive` sums across the categories of a non-time dimension but not
 * across time: a stock level or a balance (stock across warehouses adds up,
 * stock across months does not).
 *
 * Presets that encode part-of-whole (a pie, a stack, a treemap, …) refuse a
 * dataset whose measure cannot be added up along the dimension they partition by.
 */
enum Additivity: string
{
    case Additive = 'additive';
    case SemiAdditive = 'semi-additive';
    case NonAdditive = 'non-additive';
}
