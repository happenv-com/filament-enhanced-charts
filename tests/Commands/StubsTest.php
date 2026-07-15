<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Widgets\EnhancedChartWidget;
use Illuminate\Support\Str;

it('ships a stub file for every configured chart type', function () {
    $types = config('filament-enhanced-charts.chart_options');

    expect($types)->not->toBeEmpty();

    foreach ($types as $type) {
        expect(file_exists(__DIR__ . "/../../stubs/{$type}.stub"))
            ->toBeTrue("Missing stub for configured chart type [{$type}]");
    }
});

it('generates a loadable v3 widget from every stub', function () {
    // Regression: the v2 stubs overrode getOptions(): array while
    // getOption(): Option is abstract — every generated widget was a fatal.
    foreach (config('filament-enhanced-charts.chart_options') as $type) {
        $class = "Stub{$type}Widget";
        $code = strtr(file_get_contents(__DIR__ . "/../../stubs/{$type}.stub"), [
            '$NAMESPACE$' => 'StubTests',
            '$CLASS_NAME$' => $class,
            '$CHART_ID$' => (string) Str::of($class)->camel(),
        ]);

        eval(substr($code, strlen('<?php')));

        $fqcn = "StubTests\\{$class}";
        $widget = new $fqcn;

        expect($widget)->toBeInstanceOf(EnhancedChartWidget::class);

        $option = (new ReflectionMethod($widget, 'getOption'))->invoke($widget);
        expect($option)->toBeInstanceOf(Option::class);

        $options = (new ReflectionMethod($widget, 'getOptions'))->invoke($widget);
        expect($options)
            ->toBeArray()
            ->toHaveKey('series', message: "Stub [{$type}] built an option without series");
    }
});
