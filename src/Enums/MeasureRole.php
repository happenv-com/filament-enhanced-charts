<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Enums;

/**
 * What a `ChartDataset` measure means beyond "a number": one of the named
 * statistics a boxplot (`Min`, `Q1`, `Median`, `Q3`, `Max`) or a candlestick
 * (`Open`, `Close`, `Low`, `High`) is drawn from. A measure without a role
 * (`null`) is a plain value.
 *
 * A role is a description, not a restriction: presets that need no roles treat
 * every measure as a plain value, so a dataset carrying roles still draws as a
 * `line`, `bar`, … — only `boxplot` and `candlestick` require them. A dataset
 * uses each role at most once.
 */
enum MeasureRole: string
{
    case Min = 'min';
    case Q1 = 'q1';
    case Median = 'median';
    case Q3 = 'q3';
    case Max = 'max';
    case Open = 'open';
    case Close = 'close';
    case Low = 'low';
    case High = 'high';
}
