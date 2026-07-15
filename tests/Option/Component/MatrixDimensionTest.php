<?php

declare(strict_types=1);

use BcMath\Number;
use Happenv\FilamentEnhancedCharts\Option\Component\MatrixDimension;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;
use Happenv\FilamentEnhancedCharts\Option\Style\LineStyle;

covers(MatrixDimension::class);

it('builds bare-label data', function () {
    expect(MatrixDimension::make()->data(['Q1', 'Q2', 'Q3'])->toArray())->toEqual([
        'data' => ['Q1', 'Q2', 'Q3'],
    ]);
});

it('accepts data as any iterable', function () {
    $generator = (function () {
        yield 'Q1';
        yield 'Q2';
    })();

    expect(MatrixDimension::make()->data($generator)->toArray())->toEqual([
        'data' => ['Q1', 'Q2'],
    ]);
});

it('builds grouped/nested header cells with value/size/children', function () {
    expect(
        MatrixDimension::make()->data([
            ['value' => 'Xa0', 'children' => ['Xb0', 'Xb1']],
            ['value' => 'Xa1', 'size' => 80],
        ])->toArray()
    )->toEqual([
        'data' => [
            ['value' => 'Xa0', 'children' => ['Xb0', 'Xb1']],
            ['value' => 'Xa1', 'size' => 80],
        ],
    ]);
});

it('normalizes a BcMath Number inside a cell size to a marker', function () {
    expect(
        MatrixDimension::make()->data([
            ['value' => 'Xa0', 'size' => new Number('40.0')],
        ])->toArray()
    )->toEqual([
        'data' => [
            ['value' => 'Xa0', 'size' => ['__js__' => '40']],
        ],
    ]);
});

it('sets levelSize as a pixel int or a percentage string', function () {
    expect(MatrixDimension::make()->levelSize(40)->toArray())->toEqual(['levelSize' => 40]);
    expect(MatrixDimension::make()->levelSize('20%')->toArray())->toEqual(['levelSize' => '20%']);
});

it('accepts label as a builder or a plain array', function () {
    $viaBuilder = MatrixDimension::make()->label(Label::make()->fontSize(16)->color('#555'))->toArray();
    $viaArray = MatrixDimension::make()->label(['fontSize' => 16, 'color' => '#555'])->toArray();

    expect($viaBuilder)->toEqual(['label' => ['fontSize' => 16, 'color' => '#555']]);
    expect($viaArray)->toEqual($viaBuilder);
});

it('sets show, defaulting to true', function () {
    expect(MatrixDimension::make()->show()->toArray())->toEqual(['show' => true]);
    expect(MatrixDimension::make()->show(false)->toArray())->toEqual(['show' => false]);
});

it('accepts dividerLineStyle as a builder or a plain array', function () {
    $viaBuilder = MatrixDimension::make()->dividerLineStyle(LineStyle::make()->width(1)->color('#eee'))->toArray();
    $viaArray = MatrixDimension::make()->dividerLineStyle(['width' => 1, 'color' => '#eee'])->toArray();

    expect($viaBuilder)->toEqual(['dividerLineStyle' => ['width' => 1, 'color' => '#eee']]);
    expect($viaArray)->toEqual($viaBuilder);
});

it('combines data, levelSize and label together', function () {
    expect(
        MatrixDimension::make()
            ->data(['Xb0', 'Xb1'])
            ->levelSize(40)
            ->label(Label::make()->fontSize(16))
            ->show()
            ->toArray()
    )->toEqual([
        'show' => true,
        'data' => ['Xb0', 'Xb1'],
        'levelSize' => 40,
        'label' => ['fontSize' => 16],
    ]);
});

it('lets raw() override any typed key on a matrix dimension', function () {
    expect(MatrixDimension::make()->levelSize(40)->raw(['levelSize' => 60, 'show' => true])->toArray())
        ->toEqual(['levelSize' => 60, 'show' => true]);
});

it('accepts itemStyle as a builder or a plain array', function () {
    $viaBuilder = MatrixDimension::make()->itemStyle(ItemStyle::make()->color('#eee'))->toArray();
    $viaArray = MatrixDimension::make()->itemStyle(['color' => '#eee'])->toArray();

    expect($viaBuilder)->toEqual(['itemStyle' => ['color' => '#eee']]);
    expect($viaArray)->toEqual($viaBuilder);
});

it('builds per-level overrides for a nested header', function () {
    expect(
        MatrixDimension::make()->levels([
            ['itemStyle' => ['color' => '#f5f5f5']],
            ['itemStyle' => ['color' => '#eee']],
        ])->toArray()
    )->toEqual([
        'levels' => [
            ['itemStyle' => ['color' => '#f5f5f5']],
            ['itemStyle' => ['color' => '#eee']],
        ],
    ]);
});
