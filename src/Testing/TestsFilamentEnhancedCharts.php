<?php

namespace Happenv\FilamentEnhancedCharts\Testing;

use Closure;
use Livewire\Features\SupportTesting\Testable;
use PHPUnit\Framework\Assert;
use ReflectionMethod;

/**
 * Livewire {@see Testable} mixin with chart-widget assertions:
 *
 * ```php
 * livewire(RevenueChart::class)
 *     ->assertChartSeriesCount(2)
 *     ->assertChartOptions(fn (array $options): bool => $options['series'][0]['type'] === 'line');
 * ```
 *
 * @mixin Testable
 */
class TestsFilamentEnhancedCharts
{
    /** Assert on the widget's fully resolved ECharts option array. */
    public function assertChartOptions(): Closure
    {
        return function (Closure $assertion): static {
            $widget = $this->instance();
            $options = new ReflectionMethod($widget, 'getOptions')->invoke($widget);

            Assert::assertTrue(
                (bool) $assertion($options),
                'The chart options did not pass the given assertion.',
            );

            return $this;
        };
    }

    /** Assert how many series the widget's resolved option carries. */
    public function assertChartSeriesCount(): Closure
    {
        return function (int $count): static {
            $widget = $this->instance();
            $options = new ReflectionMethod($widget, 'getOptions')->invoke($widget);

            Assert::assertCount($count, $options['series'] ?? []);

            return $this;
        };
    }

    /** Assert the widget's resolved option contains a series of the given ECharts type. */
    public function assertChartHasSeries(): Closure
    {
        return function (string $type): static {
            $widget = $this->instance();
            $options = new ReflectionMethod($widget, 'getOptions')->invoke($widget);

            Assert::assertContains(
                $type,
                array_column($options['series'] ?? [], 'type'),
                "The chart has no [{$type}] series.",
            );

            return $this;
        };
    }
}
