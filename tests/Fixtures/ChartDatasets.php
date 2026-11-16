<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Tests\Fixtures;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetDimension;
use Happenv\FilamentEnhancedCharts\Data\DatasetMeasure;

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
}
