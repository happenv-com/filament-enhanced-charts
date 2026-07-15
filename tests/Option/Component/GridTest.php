<?php

use Happenv\FilamentEnhancedCharts\Option\Component\Grid;

it('sets contain label and layout edges', function () {
    expect(Grid::make()->containLabel()->left(10)->top('5%')->toArray())
        ->toEqual(['containLabel' => true, 'left' => 10, 'top' => '5%']);
});

it('sets show, width and height', function () {
    expect(Grid::make()->show(false)->width(200)->height('50%')->toArray())
        ->toEqual(['show' => false, 'width' => 200, 'height' => '50%']);
});

it('sets background/border color and width', function () {
    expect(Grid::make()->backgroundColor('#fff')->borderColor('#eee')->borderWidth(2)->toArray())
        ->toEqual(['backgroundColor' => '#fff', 'borderColor' => '#eee', 'borderWidth' => 2]);
});

it('sets zlevel and z', function () {
    expect(Grid::make()->zlevel(1)->z(2)->toArray())
        ->toEqual(['zlevel' => 1, 'z' => 2]);
});

it('sets shadow color, blur and offsets', function () {
    expect(Grid::make()->shadowColor('#333')->shadowBlur(6)->shadowOffsetX(2)->shadowOffsetY(3)->toArray())
        ->toEqual([
            'shadowColor' => '#333',
            'shadowBlur' => 6,
            'shadowOffsetX' => 2,
            'shadowOffsetY' => 3,
        ]);
});

it('does not emit keys for unset setters', function () {
    expect(Grid::make()->toArray())->toEqual([]);
});
