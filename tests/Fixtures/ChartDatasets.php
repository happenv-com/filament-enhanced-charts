<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Tests\Fixtures;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetDimension;
use Happenv\FilamentEnhancedCharts\Data\DatasetMeasure;
use Happenv\FilamentEnhancedCharts\Enums\MeasureRole;

/** Small datasets of every shape the built-in presets care about. */
final class ChartDatasets
{
    /** 1 category dimension, 1 measure. */
    public static function perChannel(): ChartDataset
    {
        return new ChartDataset(
            [DatasetDimension::category('channel', 'Channel')],
            [new DatasetMeasure('orders', 'Orders')],
            [
                ['channel' => 'Allegro', 'orders' => 12],
                ['channel' => 'Amazon', 'orders' => 8],
                ['channel' => 'eBay', 'orders' => 5],
            ],
        );
    }

    /** 1 time dimension, 2 measures. */
    public static function perMonthTwoMeasures(): ChartDataset
    {
        return new ChartDataset(
            [DatasetDimension::time('month', 'Month')],
            [new DatasetMeasure('orders', 'Orders'), new DatasetMeasure('revenue', 'Revenue')],
            [
                ['month' => 'Jan 2026', 'orders' => 10, 'revenue' => 100.5],
                ['month' => 'Feb 2026', 'orders' => 20, 'revenue' => 150],
                ['month' => 'Mar 2026', 'orders' => 15, 'revenue' => null],
            ],
        );
    }

    /** 1 category dimension, 3 measures. */
    public static function perChannelThreeMeasures(): ChartDataset
    {
        return new ChartDataset(
            [DatasetDimension::category('channel', 'Channel')],
            [new DatasetMeasure('orders', 'Orders'), new DatasetMeasure('returns', 'Returns'), new DatasetMeasure('claims', 'Claims')],
            [
                ['channel' => 'Allegro', 'orders' => 40, 'returns' => 4, 'claims' => 2],
                ['channel' => 'Amazon', 'orders' => 30, 'returns' => 6, 'claims' => 1],
            ],
        );
    }

    /** time × category, 1 measure — Allegro has no February row. */
    public static function perMonthPerChannel(): ChartDataset
    {
        return new ChartDataset(
            [DatasetDimension::time('month', 'Month'), DatasetDimension::category('channel', 'Channel')],
            [new DatasetMeasure('orders', 'Orders')],
            [
                ['month' => 'Jan', 'channel' => 'Allegro', 'orders' => 10],
                ['month' => 'Jan', 'channel' => 'Amazon', 'orders' => 4],
                ['month' => 'Feb', 'channel' => 'Amazon', 'orders' => 6],
                ['month' => 'Mar', 'channel' => 'Allegro', 'orders' => 8],
                ['month' => 'Mar', 'channel' => 'Amazon', 'orders' => 2],
            ],
        );
    }

    /** category × category, 1 measure — a flow from channel to country. */
    public static function channelToCountry(): ChartDataset
    {
        return new ChartDataset(
            [DatasetDimension::category('channel', 'Channel'), DatasetDimension::category('country', 'Country')],
            [new DatasetMeasure('orders', 'Orders')],
            [
                ['channel' => 'Allegro', 'country' => 'PL', 'orders' => 10],
                ['channel' => 'Allegro', 'country' => 'CZ', 'orders' => 3],
                ['channel' => 'Amazon', 'country' => 'DE', 'orders' => 7],
                ['channel' => 'Amazon', 'country' => 'PL', 'orders' => 2],
            ],
        );
    }

    /** No dimensions, 1 measure: a single value. */
    public static function total(): ChartDataset
    {
        return new ChartDataset([], [new DatasetMeasure('orders', 'Orders')], [['orders' => 73]]);
    }

    /** 1 time dimension of days, 1 measure. */
    public static function perDay(): ChartDataset
    {
        return new ChartDataset(
            [DatasetDimension::time('day', 'Day')],
            [new DatasetMeasure('orders', 'Orders')],
            [
                ['day' => '2026-03-03', 'orders' => 4],
                ['day' => '2026-03-01', 'orders' => 9],
                ['day' => '2026-03-02', 'orders' => 1],
            ],
        );
    }

    /** 1 region dimension, 1 measure — one code (XX) the world map does not know. */
    public static function perCountry(): ChartDataset
    {
        return new ChartDataset(
            [DatasetDimension::region('country', 'Country')],
            [new DatasetMeasure('orders', 'Orders')],
            [
                ['country' => 'PL', 'orders' => 120],
                ['country' => 'DE', 'orders' => 80],
                ['country' => 'PL', 'orders' => 30],
                ['country' => 'XX', 'orders' => 7],
                ['country' => 'FR', 'orders' => null],
                ['country' => 'CZ', 'orders' => 45],
            ],
        );
    }

    /** region × region, 1 measure — flows PL→DE twice, plus a self-loop, an unknown code and an empty flow. */
    public static function countryFlows(): ChartDataset
    {
        return new ChartDataset(
            [DatasetDimension::region('from', 'From'), DatasetDimension::region('to', 'To')],
            [new DatasetMeasure('parcels', 'Parcels')],
            [
                ['from' => 'PL', 'to' => 'DE', 'parcels' => 10],
                ['from' => 'PL', 'to' => 'DE', 'parcels' => 5],
                ['from' => 'DE', 'to' => 'FR', 'parcels' => 4],
                ['from' => 'FR', 'to' => 'FR', 'parcels' => 9],
                ['from' => 'XX', 'to' => 'PL', 'parcels' => 2],
                ['from' => 'PL', 'to' => 'CZ', 'parcels' => 0],
            ],
        );
    }

    /** 1 category dimension, the five boxplot measures (declared out of order); Amazon has no median. */
    public static function boxplot(): ChartDataset
    {
        return new ChartDataset(
            [DatasetDimension::category('channel', 'Channel')],
            [
                DatasetMeasure::withRole(MeasureRole::Median, 'median', 'Median'),
                DatasetMeasure::withRole(MeasureRole::Min, 'low', 'Min'),
                DatasetMeasure::withRole(MeasureRole::Q3, 'upper', 'Q3'),
                DatasetMeasure::withRole(MeasureRole::Q1, 'lower', 'Q1'),
                DatasetMeasure::withRole(MeasureRole::Max, 'high', 'Max'),
            ],
            [
                ['channel' => 'Allegro', 'median' => 5, 'low' => 1, 'upper' => 7, 'lower' => 3, 'high' => 9],
                ['channel' => 'Amazon', 'median' => null, 'low' => 2, 'upper' => 8, 'lower' => 4, 'high' => 10],
                ['channel' => 'eBay', 'median' => 6.5, 'low' => 2, 'upper' => 8, 'lower' => 4, 'high' => 12],
            ],
        );
    }

    /** 1 time dimension, the four candlestick measures (declared out of order). */
    public static function candles(): ChartDataset
    {
        return new ChartDataset(
            [DatasetDimension::time('day', 'Day')],
            [
                DatasetMeasure::withRole(MeasureRole::High, 'high', 'High'),
                DatasetMeasure::withRole(MeasureRole::Open, 'open', 'Open'),
                DatasetMeasure::withRole(MeasureRole::Low, 'low', 'Low'),
                DatasetMeasure::withRole(MeasureRole::Close, 'close', 'Close'),
            ],
            [
                ['day' => 'Mon', 'high' => 12, 'open' => 10, 'low' => 9, 'close' => 11],
                ['day' => 'Tue', 'high' => 13, 'open' => 11, 'low' => 8, 'close' => null],
                ['day' => 'Wed', 'high' => 12, 'open' => 11, 'low' => 7, 'close' => 8],
            ],
        );
    }

    /**
     * 1 category dimension and measures in the given units, one measure per unit entry
     * (`orders`, `revenue`, … — named `m0`, `m1`, … with the labels `M0`, `M1`, …).
     *
     * @param  list<string|null>  $units
     */
    public static function withUnits(array $units): ChartDataset
    {
        $measures = [];
        $row = ['channel' => 'Allegro'];
        $other = ['channel' => 'Amazon'];

        foreach ($units as $index => $unit) {
            $measures[] = new DatasetMeasure("m{$index}", "M{$index}", unit: $unit);
            $row["m{$index}"] = 10 * ($index + 1);
            $other["m{$index}"] = 5 * ($index + 1);
        }

        return new ChartDataset([DatasetDimension::category('channel', 'Channel')], $measures, [$row, $other]);
    }
}
