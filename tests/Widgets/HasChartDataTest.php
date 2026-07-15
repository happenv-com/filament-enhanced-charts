<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Concerns\HasChartData;
use Happenv\FilamentEnhancedCharts\Enums\ChartType;
use Happenv\FilamentEnhancedCharts\Tests\Fixtures\Widgets\QuickChartTestWidget;

use function Pest\Livewire\livewire;

covers(HasChartData::class);

function quickChartOptions(QuickChartTestWidget $widget): array
{
    return (new ReflectionMethod($widget, 'getOptions'))->invoke($widget);
}

it('builds a cartesian option from data + type without writing getOption()', function () {
    $widget = new QuickChartTestWidget;
    $widget->type = ChartType::Bar;

    $options = quickChartOptions($widget);

    expect($options['xAxis'])->toBe(['type' => 'category', 'data' => ['B2B', 'B2C']])
        ->and($options['yAxis'])->toBe(['type' => 'value'])
        ->and($options['series'])->toBe([['type' => 'bar', 'data' => [60, 40]]])
        // Option::cartesian() preset.
        ->and($options)->toHaveKey('tooltip')
        ->and($options)->toHaveKey('legend');
});

it('builds a named (pie) option without axes for a pie type', function () {
    $widget = new QuickChartTestWidget;
    $widget->type = ChartType::Pie;

    $options = quickChartOptions($widget);

    expect($options)->not->toHaveKey('xAxis')
        ->and($options)->not->toHaveKey('yAxis')
        ->and($options['series'])->toBe([['type' => 'pie', 'data' => [
            ['value' => 60, 'name' => 'B2B'],
            ['value' => 40, 'name' => 'B2C'],
        ]]]);
});

it('renders a HasChartData widget under Livewire', function () {
    livewire(QuickChartTestWidget::class)
        ->assertOk()
        ->assertChartSeriesCount(1)
        ->assertChartHasSeries('bar');
});
