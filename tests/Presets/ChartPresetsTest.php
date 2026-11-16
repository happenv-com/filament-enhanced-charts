<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Enums\DimensionKind;
use Happenv\FilamentEnhancedCharts\Exceptions\ChartPresetException;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\PieSeries;
use Happenv\FilamentEnhancedCharts\Presets\AbstractChartPreset;
use Happenv\FilamentEnhancedCharts\Presets\ChartPreset;
use Happenv\FilamentEnhancedCharts\Presets\ChartPresets;
use Happenv\FilamentEnhancedCharts\Tests\Fixtures\ChartDatasets;

covers(ChartPresets::class);

/** A custom preset registered under a given name, drawing any 1-dimension dataset as a pie. */
function customPreset(string $name, string $description = 'Custom.'): AbstractChartPreset
{
    return new class($name, $description) extends AbstractChartPreset
    {
        public function __construct(private readonly string $presetName, private readonly string $presetDescription) {}

        public function name(): string
        {
            return $this->presetName;
        }

        public function description(): string
        {
            return $this->presetDescription;
        }

        public function requirement(): string
        {
            return '1 dimension and 1 measure';
        }

        public function accepts(DatasetShape $shape): bool
        {
            return $shape->dimensionCount() === 1 && $shape->measures === 1;
        }

        protected function build(ChartDataset $dataset): Option
        {
            return Option::make()->series(PieSeries::make());
        }
    };
}

it('registers every built-in preset by default, in a stable order, with unique names', function (): void {
    $names = array_map(static fn (ChartPreset $preset): string => $preset->name(), new ChartPresets()->all());

    expect($names)->toBe([
        'pie', 'donut', 'rose', 'funnel', 'bar', 'horizontal-bar', 'line', 'smooth-line', 'area',
        'grouped-bar', 'stacked-bar', 'stacked-horizontal-bar', 'multi-line', 'stacked-area',
        'heatmap', 'sankey', 'treemap', 'sunburst', 'radar', 'polar-bar', 'gauge', 'theme-river',
        'calendar-heatmap', 'scatter', 'graph', 'chord', 'tree', 'parallel', 'pictorial-bar', 'effect-scatter',
        'boxplot', 'candlestick', 'bar-line', 'world-map', 'flow-lines',
    ])->and(array_unique($names))->toHaveCount(count($names));
});

it('is bound as a singleton', function (): void {
    expect(app(ChartPresets::class))->toBe(app(ChartPresets::class));
});

it('finds, gets and reports presets by name', function (): void {
    $registry = new ChartPresets;

    expect($registry->find('pie')?->name())->toBe('pie')
        ->and($registry->find('nope'))->toBeNull()
        ->and($registry->has('pie'))->toBeTrue()
        ->and($registry->has('nope'))->toBeFalse()
        ->and(fn (): ChartPreset => $registry->get('nope'))->toThrow(ChartPresetException::class, 'Unknown chart preset [nope]');
});

it('registers a custom preset and makes it available for matching data', function (): void {
    $registry = new ChartPresets;
    $registry->register(customPreset('my-custom'));

    $names = array_map(static fn (ChartPreset $preset): string => $preset->name(), $registry->accepting(ChartDatasets::perChannel()->shape()));

    expect($names)->toContain('my-custom')
        ->and($registry->option('my-custom', ChartDatasets::perChannel())->toArray()['series'][0]['type'])->toBe('pie');
});

it('replaces a preset registered under the same name and keeps its position', function (): void {
    $registry = new ChartPresets;
    $replacement = customPreset('bar', 'Replaced.');

    $registry->register($replacement);

    $all = $registry->all();
    $position = array_search($replacement, $all, true);

    expect($registry->find('bar'))->toBe($replacement)
        ->and($position)->toBe(4)
        ->and($all)->toHaveCount(count(ChartPresets::builtIn()));
});

it('rejects a preset whose name is not kebab-case', function (string $name): void {
    expect(fn (): ChartPresets => new ChartPresets()->register(customPreset($name)))
        ->toThrow(ChartPresetException::class, 'kebab-case');
})->with(['', 'Stacked Bar', 'stacked_bar', '-bar', 'bar-']);

it('lists only the presets accepting a shape', function (): void {
    $registry = new ChartPresets;

    $names = static fn (DatasetShape $shape): array => array_map(static fn (ChartPreset $preset): string => $preset->name(), $registry->accepting($shape));

    expect($names(DatasetShape::of(1)))->toBe(['gauge'])
        ->and($names(DatasetShape::of(1, DimensionKind::Time)))->toContain('line', 'pie', 'treemap', 'polar-bar')
        ->and($names(DatasetShape::of(1, DimensionKind::Time)))->not->toContain('gauge', 'stacked-bar', 'scatter')
        ->and($names(DatasetShape::of(1, DimensionKind::Time, DimensionKind::Category)))->toContain('stacked-bar', 'theme-river', 'heatmap', 'sankey')
        ->and($names(DatasetShape::of(5, DimensionKind::Time, DimensionKind::Time, DimensionKind::Time)))->toBe([]);
});

it('describes the presets as plain data for a chooser', function (): void {
    $registry = new ChartPresets;

    $catalog = $registry->catalog(ChartDatasets::total()->shape());

    expect($catalog)->toHaveCount(1)
        ->and($catalog[0])->toHaveKeys(['name', 'description', 'requirement'])
        ->and($catalog[0]['name'])->toBe('gauge')
        ->and($registry->catalog())->toHaveCount(35);
});

it('builds an option by preset name and refuses a dataset the preset does not accept', function (): void {
    $registry = new ChartPresets;

    expect($registry->option('pie', ChartDatasets::perChannel())->toArray()['series'][0]['type'])->toBe('pie')
        ->and(fn (): Option => $registry->option('pie', ChartDatasets::total()))
        ->toThrow(ChartPresetException::class, 'Chart preset [pie] needs 1 dimension (category, time or region) and 1 measure; the dimension must be single-valued, but the dataset has no dimensions and 1 measure');
});
