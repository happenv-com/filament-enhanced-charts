<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Option\Component\Graphic;
use Happenv\FilamentEnhancedCharts\Option\Component\Graphic\GraphicCircle;
use Happenv\FilamentEnhancedCharts\Option\Component\Graphic\GraphicElement;
use Happenv\FilamentEnhancedCharts\Option\Component\Graphic\GraphicGroup;
use Happenv\FilamentEnhancedCharts\Option\Component\Graphic\GraphicImage;
use Happenv\FilamentEnhancedCharts\Option\Component\Graphic\GraphicLine;
use Happenv\FilamentEnhancedCharts\Option\Component\Graphic\GraphicRect;
use Happenv\FilamentEnhancedCharts\Option\Component\Graphic\GraphicText;
use Happenv\FilamentEnhancedCharts\Option\Option;

covers(
    Graphic::class,
    GraphicElement::class,
    GraphicRect::class,
    GraphicCircle::class,
    GraphicText::class,
    GraphicImage::class,
    GraphicLine::class,
    GraphicGroup::class,
);

it('builds a rect element with shape and positioning', function () {
    expect(
        GraphicRect::make()
            ->left(10)
            ->top('20%')
            ->shape(['x' => 0, 'y' => 0, 'width' => 100, 'height' => 50])
            ->style(['fill' => '#fff'])
            ->toArray()
    )->toEqual([
        'type' => 'rect',
        'left' => 10,
        'top' => '20%',
        'shape' => ['x' => 0, 'y' => 0, 'width' => 100, 'height' => 50],
        'style' => ['fill' => '#fff'],
    ]);
});

it('merges repeated shape() calls on a rect instead of replacing', function () {
    expect(
        GraphicRect::make()
            ->shape(['x' => 0, 'y' => 0])
            ->shape(['width' => 100, 'height' => 50])
            ->toArray()
    )->toEqual([
        'type' => 'rect',
        'shape' => ['x' => 0, 'y' => 0, 'width' => 100, 'height' => 50],
    ]);
});

it('builds a circle element with cx/cy/r shape', function () {
    expect(
        GraphicCircle::make()->shape(['cx' => 50, 'cy' => 50, 'r' => 20])->toArray()
    )->toEqual([
        'type' => 'circle',
        'shape' => ['cx' => 50, 'cy' => 50, 'r' => 20],
    ]);
});

it('builds a line element with x1/y1/x2/y2 shape', function () {
    expect(
        GraphicLine::make()->shape(['x1' => 0, 'y1' => 0, 'x2' => 100, 'y2' => 100])->toArray()
    )->toEqual([
        'type' => 'line',
        'shape' => ['x1' => 0, 'y1' => 0, 'x2' => 100, 'y2' => 100],
    ]);
});

it('builds a text element via the text/font/textFill convenience setters', function () {
    expect(
        GraphicText::make()->text('Hello')->font('14px sans-serif')->textFill('#333')->toArray()
    )->toEqual([
        'type' => 'text',
        'style' => ['text' => 'Hello', 'font' => '14px sans-serif', 'fill' => '#333'],
    ]);
});

it('builds an image element via the image/width/height convenience setters', function () {
    expect(
        GraphicImage::make()->image('logo.png')->width(64)->height(32)->toArray()
    )->toEqual([
        'type' => 'image',
        'style' => ['image' => 'logo.png', 'width' => 64, 'height' => 32],
    ]);
});

it('sets every base positioning and interaction key on an element', function () {
    expect(
        GraphicRect::make()
            ->id('badge')
            ->left(0)
            ->right('10%')
            ->top(5)
            ->bottom('center')
            ->z(10)
            ->zlevel(1)
            ->rotation(0.5)
            ->scaleX(1.2)
            ->scaleY(0.8)
            ->origin([0.5, 0.5])
            ->cursor('pointer')
            ->draggable()
            ->silent()
            ->invisible(false)
            ->toArray()
    )->toEqual([
        'type' => 'rect',
        'left' => 0,
        'right' => '10%',
        'top' => 5,
        'bottom' => 'center',
        'id' => 'badge',
        'z' => 10,
        'zlevel' => 1,
        'rotation' => 0.5,
        'scaleX' => 1.2,
        'scaleY' => 0.8,
        'origin' => [0.5, 0.5],
        'cursor' => 'pointer',
        'draggable' => true,
        'silent' => true,
        'invisible' => false,
    ]);
});

it('builds a group with nested children', function () {
    expect(
        GraphicGroup::make()
            ->left('center')
            ->children([
                GraphicLine::make()->shape(['x1' => 0, 'y1' => 0, 'x2' => 10, 'y2' => 10]),
            ])
            ->toArray()
    )->toEqual([
        'type' => 'group',
        'left' => 'center',
        'children' => [
            ['type' => 'line', 'shape' => ['x1' => 0, 'y1' => 0, 'x2' => 10, 'y2' => 10]],
        ],
    ]);
});

it('builds a graphic component with a rect, circle, text and a group of a line', function () {
    expect(
        Graphic::make()
            ->elements([
                GraphicRect::make()->shape(['x' => 0, 'y' => 0, 'width' => 10, 'height' => 10]),
                GraphicCircle::make()->shape(['cx' => 5, 'cy' => 5, 'r' => 5]),
                GraphicText::make()->text('Total'),
                GraphicGroup::make()->children([
                    GraphicLine::make()->shape(['x1' => 0, 'y1' => 0, 'x2' => 1, 'y2' => 1]),
                ]),
            ])
            ->toArray()
    )->toEqual([
        'elements' => [
            ['type' => 'rect', 'shape' => ['x' => 0, 'y' => 0, 'width' => 10, 'height' => 10]],
            ['type' => 'circle', 'shape' => ['cx' => 5, 'cy' => 5, 'r' => 5]],
            ['type' => 'text', 'style' => ['text' => 'Total']],
            [
                'type' => 'group',
                'children' => [
                    ['type' => 'line', 'shape' => ['x1' => 0, 'y1' => 0, 'x2' => 1, 'y2' => 1]],
                ],
            ],
        ],
    ]);
});

it('accepts a raw array alongside GraphicElement builders in elements()', function () {
    expect(
        Graphic::make()
            ->elements([
                GraphicRect::make()->shape(['x' => 0, 'y' => 0, 'width' => 1, 'height' => 1]),
                ['type' => 'circle', 'shape' => ['cx' => 1, 'cy' => 1, 'r' => 1]],
            ])
            ->toArray()
    )->toEqual([
        'elements' => [
            ['type' => 'rect', 'shape' => ['x' => 0, 'y' => 0, 'width' => 1, 'height' => 1]],
            ['type' => 'circle', 'shape' => ['cx' => 1, 'cy' => 1, 'r' => 1]],
        ],
    ]);
});

it('lets raw() override any typed key on the graphic component', function () {
    expect(
        Graphic::make()
            ->elements([GraphicRect::make()])
            ->raw(['elements' => [['type' => 'circle']]])
            ->toArray()
    )->toEqual(['elements' => [['type' => 'circle']]]);
});

it('lets raw() override any typed key on a graphic element', function () {
    expect(
        GraphicRect::make()->left(0)->raw(['left' => 5, 'type' => 'circle'])->toArray()
    )->toEqual(['left' => 5, 'type' => 'circle']);
});

it('sets bounding to control how the bounding rect is computed for locating', function () {
    expect(GraphicGroup::make()->bounding('raw')->toArray())
        ->toEqual(['type' => 'group', 'bounding' => 'raw']);
});

it('merges multiple Graphic wrappers into one elements object on the option', function () {
    // Regression: two Graphics used to emit a LIST of {elements} wrappers,
    // which is not a valid ECharts top-level `graphic` shape.
    expect(
        Option::make()
            ->graphic(
                Graphic::make()->elements([GraphicRect::make()->shape(['x' => 0, 'y' => 0, 'width' => 1, 'height' => 1])]),
                Graphic::make()->elements([GraphicCircle::make()->shape(['cx' => 1, 'cy' => 1, 'r' => 1])]),
            )
            ->toArray()
    )->toEqual([
        'graphic' => [
            'elements' => [
                ['type' => 'rect', 'shape' => ['x' => 0, 'y' => 0, 'width' => 1, 'height' => 1]],
                ['type' => 'circle', 'shape' => ['cx' => 1, 'cy' => 1, 'r' => 1]],
            ],
        ],
    ]);
});

it('accepts bare GraphicElement builders on Option::graphic()', function () {
    expect(
        Option::make()
            ->graphic(
                GraphicText::make()->text('Total'),
                GraphicCircle::make()->shape(['cx' => 1, 'cy' => 1, 'r' => 1]),
            )
            ->toArray()
    )->toEqual([
        'graphic' => [
            'elements' => [
                ['type' => 'text', 'style' => ['text' => 'Total']],
                ['type' => 'circle', 'shape' => ['cx' => 1, 'cy' => 1, 'r' => 1]],
            ],
        ],
    ]);
});

it('emits a single Graphic wrapper verbatim (raw keys preserved)', function () {
    expect(
        Option::make()
            ->graphic(Graphic::make()->elements([GraphicRect::make()])->raw(['id' => 'overlay']))
            ->toArray()
    )->toEqual([
        'graphic' => ['elements' => [['type' => 'rect']], 'id' => 'overlay'],
    ]);
});
