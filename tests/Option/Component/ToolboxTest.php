<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Enums\Orient;
use Happenv\FilamentEnhancedCharts\Option\Component\Toolbox;
use Happenv\FilamentEnhancedCharts\Option\Component\Toolbox\DataView;
use Happenv\FilamentEnhancedCharts\Option\Component\Toolbox\MagicType;
use Happenv\FilamentEnhancedCharts\Option\Component\Toolbox\Restore;
use Happenv\FilamentEnhancedCharts\Option\Component\Toolbox\SaveAsImage;

it('builds an empty toolbox when nothing is set', function () {
    expect(Toolbox::make()->toArray())->toEqual([]);
});

it('registers a saveAsImage feature with its title and type', function () {
    expect(
        Toolbox::make()
            ->feature(SaveAsImage::make()->title('Zapisz')->type('png'))
            ->toArray()
    )->toEqual([
        'feature' => [
            'saveAsImage' => ['title' => 'Zapisz', 'type' => 'png'],
        ],
    ]);
});

it('registers a magicType feature remembering its switchable types', function () {
    expect(Toolbox::make()->feature(MagicType::make(['line', 'bar']))->toArray())
        ->toEqual([
            'feature' => [
                'magicType' => ['type' => ['line', 'bar']],
            ],
        ]);
});

it('registers a dataView feature with readOnly', function () {
    expect(Toolbox::make()->feature(DataView::make()->readOnly())->toArray())
        ->toEqual([
            'feature' => [
                'dataView' => ['readOnly' => true],
            ],
        ]);
});

it('registers multiple features together, keyed by their feature key', function () {
    expect(
        Toolbox::make()
            ->feature(SaveAsImage::make(), Restore::make(), DataView::make()->readOnly(false))
            ->toArray()
    )->toEqual([
        'feature' => [
            'saveAsImage' => [],
            'restore' => [],
            'dataView' => ['readOnly' => false],
        ],
    ]);
});

it('positions the toolbox via HasLayout', function () {
    expect(Toolbox::make()->right(0)->top('top')->toArray())
        ->toEqual(['right' => 0, 'top' => 'top']);
});

it('sets orient from the enum or a raw string', function () {
    expect(Toolbox::make()->orient(Orient::Vertical)->toArray())
        ->toEqual(['orient' => 'vertical']);

    expect(Toolbox::make()->orient('horizontal')->toArray())
        ->toEqual(['orient' => 'horizontal']);
});

it('emits show, itemSize, itemGap and showTitle when set', function () {
    expect(Toolbox::make()->show()->itemSize(20)->itemGap(12)->showTitle(false)->toArray())
        ->toEqual([
            'show' => true,
            'itemSize' => 20,
            'itemGap' => 12,
            'showTitle' => false,
        ]);
});
