<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Concerns\HasChartPreset;
use Happenv\FilamentEnhancedCharts\Exceptions\ChartPresetException;
use Happenv\FilamentEnhancedCharts\Tests\Fixtures\Widgets\PresetChartTestWidget;

use function Pest\Livewire\livewire;

covers(HasChartPreset::class);

function presetWidgetOptions(PresetChartTestWidget $widget): array
{
    return new ReflectionMethod($widget, 'getOptions')->invoke($widget);
}

it('builds the option from the dataset and the preset name without writing getOption()', function (): void {
    $options = presetWidgetOptions(new PresetChartTestWidget);

    expect($options['xAxis']['data'])->toBe(['Jan', 'Feb', 'Mar'])
        ->and(array_column($options['series'], 'name'))->toBe(['Allegro', 'Amazon'])
        ->and($options['series'][0]['stack'])->toBe('total');
});

it('switches preset by name', function (): void {
    $widget = new PresetChartTestWidget;
    $widget->preset = 'heatmap';

    expect(presetWidgetOptions($widget)['series'][0]['type'])->toBe('heatmap');
});

it('fails loudly for an unknown preset instead of rendering a blank chart', function (): void {
    $widget = new PresetChartTestWidget;
    $widget->preset = 'no-such-chart';

    expect(fn (): array => presetWidgetOptions($widget))->toThrow(ChartPresetException::class, 'Unknown chart preset [no-such-chart]');
});

it('fails loudly for a preset that does not accept the dataset', function (): void {
    $widget = new PresetChartTestWidget;
    $widget->totalOnly = true;

    expect(fn (): array => presetWidgetOptions($widget))->toThrow(ChartPresetException::class, 'Chart preset [stacked-bar] needs');
});

it('renders a HasChartPreset widget under Livewire', function (): void {
    livewire(PresetChartTestWidget::class)
        ->assertOk()
        ->assertChartSeriesCount(2)
        ->assertChartHasSeries('bar');
});
