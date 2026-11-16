<?php

declare(strict_types=1);

use Filament\Support\Facades\FilamentAsset;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\MapSeries;
use Happenv\FilamentEnhancedCharts\Presets\ChartPresets;
use Happenv\FilamentEnhancedCharts\Support\ChartMaps;
use Happenv\FilamentEnhancedCharts\Tests\Fixtures\ChartDatasets as D;
use Happenv\FilamentEnhancedCharts\Tests\Fixtures\Widgets\PresetChartTestWidget;

use function Pest\Livewire\livewire;

covers(ChartMaps::class);

it('asks for no map at all for a chart that draws none', function (string $preset): void {
    $option = new ChartPresets()->option($preset, D::perChannel());

    expect(ChartMaps::for($option))->toBe([])
        ->and(ChartMaps::for($option->toArray()))->toBe([]);
})->with(['pie', 'bar', 'treemap', 'pictorial-bar']);

it('asks for the world map when the option uses it, in a map series or a geo component', function (string $preset, mixed $dataset): void {
    $maps = ChartMaps::for(new ChartPresets()->option($preset, $dataset()));

    expect(array_keys($maps))->toBe(['filament-enhanced-charts-world'])
        ->and($maps['filament-enhanced-charts-world'])->toContain('filament-enhanced-charts-map-world');
})->with([
    'world-map' => ['world-map', D::perCountry(...)],
    'flow-lines' => ['flow-lines', D::countryFlows(...)],
]);

it('leaves a map of the app alone', function (): void {
    $option = Option::make()->series(MapSeries::make()->map('poland'));

    expect(ChartMaps::for($option))->toBe([]);
});

it('registers the map as an asset, which costs a page nothing until an option requests it', function (): void {
    $src = FilamentAsset::getAlpineComponentSrc(ChartMaps::WORLD_ASSET, 'happenv/filament-enhanced-charts');

    expect($src)->toContain(ChartMaps::WORLD_ASSET)
        ->and(is_file(__DIR__ . '/../../resources/dist/' . ChartMaps::WORLD_ASSET . '.js'))->toBeTrue();
});

it('lets a preset widget supply the map by itself, only when its chart draws one', function (): void {
    $plain = new PresetChartTestWidget;
    $plain->readyToLoad = true;

    $map = new PresetChartTestWidget;
    $map->readyToLoad = true;
    $map->preset = 'world-map';
    $map->perCountry = true;

    $notReady = new PresetChartTestWidget;
    $notReady->preset = 'world-map';
    $notReady->perCountry = true;

    expect($plain->getMaps())->toBe([])
        ->and(array_keys($map->getMaps()))->toBe(['filament-enhanced-charts-world'])
        ->and($notReady->getMaps())->toBe([]);
});

it('hands the map to the page only for the chart that needs it', function (): void {
    expect(livewire(PresetChartTestWidget::class)->html())->not->toContain(ChartMaps::WORLD_ASSET)
        ->and(livewire(PresetChartTestWidget::class, ['preset' => 'world-map', 'perCountry' => true])->html())->toContain(ChartMaps::WORLD_ASSET);
});

it('sends the map along with a changed option, so a chart switched to a map can register it', function (): void {
    livewire(PresetChartTestWidget::class, ['preset' => 'bar', 'perCountry' => true])
        ->set('preset', 'world-map')
        ->assertDispatched('updateOptions', fn (string $name, array $params): bool => array_keys($params['maps']) === [ChartMaps::WORLD]);
});
