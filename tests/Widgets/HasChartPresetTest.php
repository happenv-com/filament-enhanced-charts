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

it('hands a mounted widget the whole new structure when its preset changes', function (): void {
    $dispatched = [];

    livewire(PresetChartTestWidget::class)
        ->set('preset', 'heatmap')
        ->assertDispatched('updateOptions', function (string $name, array $params) use (&$dispatched): bool {
            $dispatched[] = $params['options'];

            return true;
        })
        ->set('preset', 'stacked-bar')
        ->assertDispatched('updateOptions', function (string $name, array $params) use (&$dispatched): bool {
            $dispatched[] = $params['options'];

            return true;
        });

    $heatmap = $dispatched[0];
    $stackedBar = end($dispatched);

    // The client replaces the painted option when this structure changes, so the
    // option sent for the new preset must itself be complete and free of the old one's parts.
    expect($heatmap)->toHaveKey('visualMap')
        ->and($heatmap['series'][0]['type'])->toBe('heatmap')
        ->and($stackedBar)->not->toHaveKey('visualMap')
        ->and($stackedBar['series'][0]['type'])->toBe('bar')
        ->and($stackedBar['yAxis'])->not->toHaveKey('inverse');
});
