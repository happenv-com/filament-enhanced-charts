<?php

use Happenv\FilamentEnhancedCharts\Option\Axis\CategoryAxis;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\LineSeries;
use Happenv\FilamentEnhancedCharts\Widgets\EnhancedChartWidget;

it('renders getOption() through getOptions() as an array', function () {
    $widget = new class extends EnhancedChartWidget
    {
        protected function getOption(): Option
        {
            return Option::make()
                ->xAxis(CategoryAxis::make()->data(['Jan']))
                ->series(LineSeries::make()->data([1]));
        }

        public function options(): array
        {
            return $this->getOptions();
        }
    };

    expect($widget->options())->toBe([
        'xAxis' => ['type' => 'category', 'data' => ['Jan']],
        'yAxis' => ['type' => 'value'], // defaulted for a cartesian chart
        'series' => [['type' => 'line', 'data' => [1]]],
    ]);
});
