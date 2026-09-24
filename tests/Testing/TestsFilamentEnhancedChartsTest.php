<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Option\Axis\CategoryAxis;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\BarSeries;
use Happenv\FilamentEnhancedCharts\Option\Series\LineSeries;
use Happenv\FilamentEnhancedCharts\Testing\TestsFilamentEnhancedCharts;
use Happenv\FilamentEnhancedCharts\Widgets\EnhancedChartWidget;
use PHPUnit\Framework\AssertionFailedError;

covers(TestsFilamentEnhancedCharts::class);

/** Binds a mixin closure to a Testable-like host exposing instance(). */
function bindEChartsMixinAssertion(Closure $method): Closure
{
    $widget = new class extends EnhancedChartWidget
    {
        protected function getOption(): Option
        {
            return Option::make()
                ->xAxis(CategoryAxis::make()->data(['a', 'b']))
                ->series(
                    LineSeries::make()->data([1, 2]),
                    BarSeries::make()->data([3, 4]),
                );
        }
    };

    $host = new readonly class($widget)
    {
        public function __construct(private EnhancedChartWidget $widget) {}

        public function instance(): EnhancedChartWidget
        {
            return $this->widget;
        }
    };

    return Closure::bind($method, $host, $host::class);
}

it('asserts on the resolved chart options', function (): void {
    $mixin = new TestsFilamentEnhancedCharts;

    bindEChartsMixinAssertion($mixin->assertChartOptions())(
        fn (array $options): bool => ($options['series'][0]['type'] ?? null) === 'line',
    );
});

it('asserts the series count', function (): void {
    $mixin = new TestsFilamentEnhancedCharts;

    bindEChartsMixinAssertion($mixin->assertChartSeriesCount())(2);
});

it('asserts a series type is present', function (): void {
    $mixin = new TestsFilamentEnhancedCharts;

    bindEChartsMixinAssertion($mixin->assertChartHasSeries())('bar');
});

it('fails when the series type is absent', function (): void {
    $mixin = new TestsFilamentEnhancedCharts;

    bindEChartsMixinAssertion($mixin->assertChartHasSeries())('pie');
})->throws(AssertionFailedError::class);
