<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetDimension;
use Happenv\FilamentEnhancedCharts\Data\DatasetMeasure;
use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Enums\Additivity;
use Happenv\FilamentEnhancedCharts\Enums\DimensionKind;
use Happenv\FilamentEnhancedCharts\Exceptions\ChartPresetException;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\PieSeries;
use Happenv\FilamentEnhancedCharts\Presets\AbstractChartPreset;
use Happenv\FilamentEnhancedCharts\Presets\ChartPreset;
use Happenv\FilamentEnhancedCharts\Presets\ChartPresets;
use Happenv\FilamentEnhancedCharts\Presets\EncodesPartOfWhole;
use Happenv\FilamentEnhancedCharts\Presets\Support\PartOfWholeGuard;

covers(PartOfWholeGuard::class);

/**
 * Per part-of-whole built-in: a shape it accepts when single-valued, and the
 * positions that, made multi-valued one at a time, must each make it refuse.
 *
 * @return array<string, array{DatasetShape, list<int>}>
 */
function partOfWholeCases(): array
{
    $one = DatasetShape::of(1, DimensionKind::Category);
    $two = DatasetShape::of(1, DimensionKind::Category, DimensionKind::Category);

    return [
        'pie' => [$one, [0]],
        'donut' => [$one, [0]],
        'rose' => [$one, [0]],
        'funnel' => [$one, [0]],
        'treemap' => [$two, [0, 1]],
        'sunburst' => [$two, [0, 1]],
        'tree' => [DatasetShape::of(1, DimensionKind::Category, DimensionKind::Category, DimensionKind::Category), [0, 1, 2]],
        'sankey' => [$two, [0, 1]],
        'chord' => [$two, [0, 1]],
        'graph' => [$two, [0, 1]],
        'stacked-bar' => [$two, [1]],
        'stacked-horizontal-bar' => [$two, [1]],
        'stacked-area' => [$two, [1]],
        'theme-river' => [DatasetShape::of(1, DimensionKind::Time, DimensionKind::Category), [1]],
    ];
}

it('refuses every part-of-whole built-in when a dimension it partitions by is multi-valued', function (string $name, DatasetShape $shape, array $partitioning): void {
    $registry = new ChartPresets;
    $preset = $registry->get($name);

    expect($preset)->toBeInstanceOf(EncodesPartOfWhole::class)
        ->and($preset->accepts($shape))->toBeTrue()
        ->and($preset->requirement())->toContain('single-valued');

    foreach ($partitioning as $index) {
        $multi = $shape->withMultiValued($index);

        expect($preset->accepts($multi))->toBeFalse("{$name} must refuse position {$index}")
            ->and(array_map(static fn (ChartPreset $p): string => $p->name(), $registry->accepting($multi)))->not->toContain($name)
            ->and(array_column($registry->catalog($multi), 'name'))->not->toContain($name);
    }
})->with(fn (): array => array_map(
    static fn (string $name, array $case): array => [$name, $case[0], $case[1]],
    array_keys(partOfWholeCases()),
    partOfWholeCases(),
));

it('lets the x axis of a stacked or theme-river chart be multi-valued, since only the stacked dimension adds up', function (): void {
    $registry = new ChartPresets;

    expect($registry->get('stacked-bar')->accepts(DatasetShape::of(1, DimensionKind::Category, DimensionKind::Category)->withMultiValued(0)))->toBeTrue()
        ->and($registry->get('theme-river')->accepts(DatasetShape::of(1, DimensionKind::Time, DimensionKind::Category)->withMultiValued(0)))->toBeTrue();
});

it('still accepts a multi-valued dimension on presets that encode no part-of-whole', function (string $name, DatasetShape $shape): void {
    $multi = $shape->withMultiValued(...array_keys($shape->dimensions));

    expect((new ChartPresets)->get($name)->accepts($multi))->toBeTrue();
})->with([
    'bar' => ['bar', DatasetShape::of(1, DimensionKind::Category)],
    'horizontal-bar' => ['horizontal-bar', DatasetShape::of(1, DimensionKind::Category)],
    'line' => ['line', DatasetShape::of(1, DimensionKind::Time)],
    'area' => ['area', DatasetShape::of(1, DimensionKind::Time)],
    'polar-bar' => ['polar-bar', DatasetShape::of(1, DimensionKind::Category)],
    'grouped-bar' => ['grouped-bar', DatasetShape::of(1, DimensionKind::Category, DimensionKind::Category)],
    'multi-line' => ['multi-line', DatasetShape::of(1, DimensionKind::Time, DimensionKind::Category)],
    'heatmap' => ['heatmap', DatasetShape::of(1, DimensionKind::Category, DimensionKind::Category)],
    'radar' => ['radar', DatasetShape::of(1, DimensionKind::Category, DimensionKind::Category)],
    'scatter' => ['scatter', DatasetShape::of(2, DimensionKind::Category)],
]);

it('does not stack several measures of a single dimension as parts of a dimension total', function (): void {
    $shape = DatasetShape::of(2, DimensionKind::Category)->withMultiValued(0);

    expect((new ChartPresets)->get('stacked-bar')->accepts($shape))->toBeTrue();
});

it('filters the catalog by the multi-valued flag of the dataset', function (): void {
    $registry = new ChartPresets;
    $single = DatasetShape::of(1, DimensionKind::Category);
    $multi = $single->withMultiValued(0);

    $singleNames = array_column($registry->catalog($single), 'name');
    $multiNames = array_column($registry->catalog($multi), 'name');

    expect($singleNames)->toContain('pie', 'funnel', 'bar')
        ->and($multiNames)->not->toContain('pie', 'donut', 'rose', 'funnel')
        ->and($multiNames)->toContain('bar', 'line', 'polar-bar')
        ->and(count($registry->catalog()))->toBe(count($registry->all()));
});

it('refuses to build a part-of-whole chart from a dataset with a multi-valued dimension', function (): void {
    $dataset = new ChartDataset(
        [DatasetDimension::category('tag', 'Tag')->multiValued()],
        [new DatasetMeasure('orders', 'Orders')],
        [['tag' => 'gift', 'orders' => 3], ['tag' => 'sale', 'orders' => 3]],
    );
    $registry = new ChartPresets;

    expect(fn (): Option => $registry->option('pie', $dataset))
        ->toThrow(ChartPresetException::class, 'category (multi-valued)')
        ->and($registry->option('bar', $dataset)->toArray()['series'][0]['type'])->toBe('bar');
});

it('keeps a custom preset that knows nothing about part-of-whole working', function (): void {
    $legacy = new class implements ChartPreset
    {
        public function name(): string
        {
            return 'legacy';
        }

        public function description(): string
        {
            return 'A third-party preset written against the v1.5 contract.';
        }

        public function requirement(): string
        {
            return '1 dimension and 1 measure';
        }

        public function accepts(DatasetShape $shape): bool
        {
            return $shape->dimensionCount() === 1;
        }

        public function option(ChartDataset $dataset): Option
        {
            return Option::make()->series(PieSeries::make());
        }
    };
    $registry = (new ChartPresets)->register($legacy);
    $multi = DatasetShape::of(1, DimensionKind::Category)->withMultiValued(0);

    expect($legacy)->not->toBeInstanceOf(EncodesPartOfWhole::class)
        ->and(PartOfWholeGuard::refuses($legacy, $multi))->toBeFalse()
        ->and(array_map(static fn (ChartPreset $p): string => $p->name(), $registry->accepting($multi)))->toContain('legacy');
});

it('guards a custom preset extending AbstractChartPreset that overrides accepts() and opts in', function (): void {
    $custom = new class extends AbstractChartPreset
    {
        public function name(): string
        {
            return 'share-bar';
        }

        public function description(): string
        {
            return 'Custom.';
        }

        public function requirement(): string
        {
            return '1 dimension and 1 measure; the dimension must be single-valued';
        }

        public function accepts(DatasetShape $shape): bool
        {
            return $shape->dimensionCount() === 1;
        }

        public function partitioningDimensions(DatasetShape $shape): array
        {
            return [0];
        }

        protected function build(ChartDataset $dataset): Option
        {
            return Option::make()->series(PieSeries::make());
        }
    };
    $multiDataset = new ChartDataset(
        [DatasetDimension::category('tag', 'Tag', multiValued: true)],
        [new DatasetMeasure('orders', 'Orders')],
        [['tag' => 'gift', 'orders' => 1]],
    );
    $registry = (new ChartPresets)->register($custom);

    expect(array_column($registry->catalog(DatasetShape::of(1, DimensionKind::Category)->withMultiValued(0)), 'name'))->not->toContain('share-bar')
        ->and(fn (): Option => $custom->option($multiDataset))->toThrow(ChartPresetException::class, 'share-bar');
});

it('keeps a custom preset that overrides accepts() without opting in working on a multi-valued dataset', function (): void {
    $plain = new class extends AbstractChartPreset
    {
        public function name(): string
        {
            return 'plain';
        }

        public function description(): string
        {
            return 'Custom.';
        }

        public function requirement(): string
        {
            return '1 dimension and 1 measure';
        }

        public function accepts(DatasetShape $shape): bool
        {
            return $shape->dimensionCount() === 1;
        }

        protected function build(ChartDataset $dataset): Option
        {
            return Option::make()->series(PieSeries::make());
        }
    };
    $dataset = new ChartDataset(
        [DatasetDimension::category('tag', 'Tag', multiValued: true)],
        [new DatasetMeasure('orders', 'Orders')],
        [['tag' => 'gift', 'orders' => 1]],
    );

    expect($plain->option($dataset)->toArray()['series'][0]['type'])->toBe('pie');
});

it('refuses a multi-valued dataset in the registry for a preset implementing both interfaces directly', function (): void {
    $direct = new class implements ChartPreset, EncodesPartOfWhole
    {
        public function name(): string
        {
            return 'direct-share';
        }

        public function description(): string
        {
            return 'Implements the contracts without the abstract base.';
        }

        public function requirement(): string
        {
            return '1 dimension and 1 measure; the dimension must be single-valued';
        }

        public function accepts(DatasetShape $shape): bool
        {
            return $shape->dimensionCount() === 1;
        }

        public function partitioningDimensions(DatasetShape $shape): array
        {
            return [0];
        }

        public function option(ChartDataset $dataset): Option
        {
            return Option::make()->series(PieSeries::make());
        }
    };
    $registry = (new ChartPresets)->register($direct);
    $rows = [['tag' => 'gift', 'orders' => 1]];
    $single = new ChartDataset([DatasetDimension::category('tag', 'Tag')], [new DatasetMeasure('orders', 'Orders')], $rows);
    $multi = new ChartDataset([DatasetDimension::category('tag', 'Tag', multiValued: true)], [new DatasetMeasure('orders', 'Orders')], $rows);

    expect($registry->option('direct-share', $single)->toArray()['series'][0]['type'])->toBe('pie')
        ->and(fn (): Option => $registry->option('direct-share', $multi))->toThrow(ChartPresetException::class, 'direct-share');
});

/** A one-row dataset of the shape's dimensions (`d0`, `d1`, …) and one measure `ratio` with the given additivity. */
function datasetOfShape(DatasetShape $shape, Additivity $additivity): ChartDataset
{
    $dimensions = [];
    $row = [];
    foreach ($shape->dimensions as $index => $kind) {
        $dimensions[] = new DatasetDimension("d{$index}", "Dimension {$index}", $kind);
        $row["d{$index}"] = $kind === DimensionKind::Region ? 'PL' : "v{$index}";
    }

    return new ChartDataset($dimensions, [new DatasetMeasure('ratio', 'Margin %')->withAdditivity($additivity)], [$row + ['ratio' => 12.5]]);
}

it('leaves an additive measure untouched on every part-of-whole built-in', function (string $name, DatasetShape $shape): void {
    $dataset = datasetOfShape($shape, Additivity::Additive);

    expect((new ChartPresets)->get($name)->accepts($dataset->shape()))->toBeTrue()
        ->and((new ChartPresets)->option($name, $dataset))->toBeInstanceOf(Option::class);
})->with(fn (): array => array_map(
    static fn (string $name, array $case): array => [$name, $case[0]],
    array_keys(partOfWholeCases()),
    partOfWholeCases(),
));

it('refuses a non-additive measure on every part-of-whole built-in, naming the measure and suggesting other presets', function (string $name, DatasetShape $shape): void {
    $dataset = datasetOfShape($shape, Additivity::NonAdditive);
    $registry = new ChartPresets;

    expect($registry->get($name)->accepts($dataset->shape()))->toBeFalse()
        ->and(array_column($registry->catalog($dataset->shape()), 'name'))->not->toContain($name)
        ->and(fn (): Option => $registry->option($name, $dataset))->toThrow(ChartPresetException::class, 'Margin %')
        ->and(fn (): Option => $registry->get($name)->option($dataset))->toThrow(ChartPresetException::class, 'do not add up to a whole');
})->with(fn (): array => array_map(
    static fn (string $name, array $case): array => [$name, $case[0]],
    array_keys(partOfWholeCases()),
    partOfWholeCases(),
));

it('suggests presets that split no total in the refusal', function (): void {
    $dataset = datasetOfShape(DatasetShape::of(1, DimensionKind::Category), Additivity::NonAdditive);

    try {
        (new ChartPresets)->option('pie', $dataset);
        $this->fail('pie must refuse a non-additive measure');
    } catch (ChartPresetException $exception) {
        expect($exception->getMessage())->toContain('Use a preset that does not split a total', 'bar')->not->toContain('pie, ');
    }
});

it('keeps non-additive measures on presets that encode no part-of-whole', function (string $name, DatasetShape $shape): void {
    $dataset = datasetOfShape($shape, Additivity::NonAdditive);

    expect((new ChartPresets)->option($name, $dataset))->toBeInstanceOf(Option::class);
})->with([
    'bar' => ['bar', DatasetShape::of(1, DimensionKind::Category)],
    'line' => ['line', DatasetShape::of(1, DimensionKind::Time)],
    'grouped-bar' => ['grouped-bar', DatasetShape::of(1, DimensionKind::Category, DimensionKind::Category)],
    'heatmap' => ['heatmap', DatasetShape::of(1, DimensionKind::Category, DimensionKind::Category)],
]);

it('lets a semi-additive measure be split by a category but not summed across time', function (): void {
    $registry = new ChartPresets;
    $byCategory = datasetOfShape(DatasetShape::of(1, DimensionKind::Category), Additivity::SemiAdditive);
    $byTime = datasetOfShape(DatasetShape::of(1, DimensionKind::Time), Additivity::SemiAdditive);
    $timeIntoCategories = datasetOfShape(DatasetShape::of(1, DimensionKind::Time, DimensionKind::Category), Additivity::SemiAdditive);
    $timeInHierarchy = datasetOfShape(DatasetShape::of(1, DimensionKind::Time, DimensionKind::Category), Additivity::SemiAdditive);

    expect($registry->option('pie', $byCategory))->toBeInstanceOf(Option::class)
        ->and($registry->option('theme-river', $timeIntoCategories))->toBeInstanceOf(Option::class)
        ->and($registry->option('stacked-bar', $timeIntoCategories))->toBeInstanceOf(Option::class)
        ->and($registry->get('pie')->accepts($byTime->shape()))->toBeFalse()
        ->and(fn (): Option => $registry->option('pie', $byTime))->toThrow(ChartPresetException::class, 'Dimension 0')
        ->and(fn (): Option => $registry->option('sunburst', $timeInHierarchy))->toThrow(ChartPresetException::class, 'add up across categories but not across time')
        ->and($registry->option('line', $byTime))->toBeInstanceOf(Option::class);
});

it('still refuses a multi-valued dimension with the generic message when the measure is additive', function (): void {
    $dataset = new ChartDataset(
        [DatasetDimension::category('tag', 'Tag')->multiValued()],
        [new DatasetMeasure('orders', 'Orders')->additive()],
        [['tag' => 'gift', 'orders' => 3]],
    );

    expect(fn (): Option => (new ChartPresets)->option('pie', $dataset))->toThrow(ChartPresetException::class, 'category (multi-valued)');
});

it('translates the additivity refusal', function (): void {
    $dataset = datasetOfShape(DatasetShape::of(1, DimensionKind::Category), Additivity::NonAdditive);

    app()->setLocale('pl');

    expect(fn (): Option => (new ChartPresets)->option('pie', $dataset))->toThrow(ChartPresetException::class, 'nie sumują się do całości');
});

it('guards a custom preset that opts in and a direct implementation alike', function (): void {
    $custom = new class extends AbstractChartPreset
    {
        public function name(): string
        {
            return 'share-bar';
        }

        public function description(): string
        {
            return 'Custom.';
        }

        public function requirement(): string
        {
            return '1 dimension and 1 measure; the measure must be additive';
        }

        protected function acceptsStructure(DatasetShape $shape): bool
        {
            return $shape->dimensionCount() === 1;
        }

        public function partitioningDimensions(DatasetShape $shape): array
        {
            return [0];
        }

        protected function build(ChartDataset $dataset): Option
        {
            return Option::make()->series(PieSeries::make());
        }
    };
    $dataset = datasetOfShape(DatasetShape::of(1, DimensionKind::Category), Additivity::NonAdditive);
    $registry = (new ChartPresets)->register($custom);

    expect(fn (): Option => $custom->option($dataset))->toThrow(ChartPresetException::class, 'share-bar')
        ->and(fn (): Option => $registry->option('share-bar', $dataset))->toThrow(ChartPresetException::class, 'Margin %');
});

it('refuses stacking non-additive measures of one dimension, but not comparing them', function (string $name): void {
    $dataset = new ChartDataset(
        [DatasetDimension::category('channel', 'Channel')],
        [new DatasetMeasure('margin', 'Margin %', unit: '%')->nonAdditive(), new DatasetMeasure('returns', 'Returns %', unit: '%')->nonAdditive()],
        [['channel' => 'A', 'margin' => 10, 'returns' => 2]],
    );
    $registry = new ChartPresets;

    expect($registry->get($name)->accepts($dataset->shape()))->toBeFalse()
        ->and(fn (): Option => $registry->option($name, $dataset))->toThrow(ChartPresetException::class, 'Margin %')
        ->and($registry->option('grouped-bar', $dataset))->toBeInstanceOf(Option::class)
        ->and($registry->option('stacked-bar', new ChartDataset($dataset->dimensions, [new DatasetMeasure('margin', 'Margin %'), new DatasetMeasure('returns', 'Returns')], $dataset->rows)))->toBeInstanceOf(Option::class);
})->with(['stacked-bar', 'stacked-horizontal-bar', 'stacked-area']);
