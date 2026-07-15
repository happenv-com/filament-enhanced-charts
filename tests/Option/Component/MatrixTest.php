<?php

declare(strict_types=1);

use BcMath\Number;
use Filament\Support\Colors\Color;
use Happenv\FilamentEnhancedCharts\Option\Component\Matrix;
use Happenv\FilamentEnhancedCharts\Option\Component\MatrixDimension;

covers(Matrix::class);

it('builds x and y from MatrixDimension builders', function () {
    expect(
        Matrix::make()
            ->x(MatrixDimension::make()->data(['Q1', 'Q2'])->levelSize(40))
            ->y(MatrixDimension::make()->data(['2024', '2025'])->levelSize(70))
            ->toArray()
    )->toEqual([
        'x' => ['data' => ['Q1', 'Q2'], 'levelSize' => 40],
        'y' => ['data' => ['2024', '2025'], 'levelSize' => 70],
    ]);
});

it('accepts x and y as plain arrays', function () {
    expect(
        Matrix::make()
            ->x(['data' => ['Q1', 'Q2'], 'levelSize' => 40])
            ->y(['data' => ['2024', '2025'], 'levelSize' => 70])
            ->toArray()
    )->toEqual([
        'x' => ['data' => ['Q1', 'Q2'], 'levelSize' => 40],
        'y' => ['data' => ['2024', '2025'], 'levelSize' => 70],
    ]);
});

it('normalizes a BcMath Number nested inside an x builder', function () {
    expect(
        Matrix::make()->x(MatrixDimension::make()->levelSize(0)->data([
            ['value' => 'Q1', 'size' => new Number('40.0')],
        ]))->toArray()
    )->toEqual([
        'x' => [
            'levelSize' => 0,
            'data' => [
                ['value' => 'Q1', 'size' => ['__js__' => '40']],
            ],
        ],
    ]);
});

it('builds the corner from specific cell definitions addressed by negative coord', function () {
    expect(
        Matrix::make()->corner([
            'data' => [
                ['coord' => [-1, -1], 'value' => 'Time'],
            ],
            'label' => ['fontSize' => 16],
        ])->toArray()
    )->toEqual([
        'corner' => [
            'data' => [
                ['coord' => [-1, -1], 'value' => 'Time'],
            ],
            'label' => ['fontSize' => 16],
        ],
    ]);
});

it('builds the body from specific cell definitions addressed by coord', function () {
    expect(
        Matrix::make()->body([
            'data' => [
                ['coord' => [null, 3], 'coordClamp' => true, 'mergeCells' => true, 'value' => 'Break'],
            ],
        ])->toArray()
    )->toEqual([
        'body' => [
            'data' => [
                ['coord' => [null, 3], 'coordClamp' => true, 'mergeCells' => true, 'value' => 'Break'],
            ],
        ],
    ]);
});

it('normalizes a BcMath Number nested inside body cell data', function () {
    expect(
        Matrix::make()->body([
            'data' => [
                ['coord' => [0, 0], 'value' => new Number('12.50')],
            ],
        ])->toArray()
    )->toEqual([
        'body' => [
            'data' => [
                ['coord' => [0, 0], 'value' => ['__js__' => '12.5']],
            ],
        ],
    ]);
});

it('applies left/right/top/bottom layout via HasLayout', function () {
    expect(Matrix::make()->left('center')->top(30)->bottom(80)->toArray())->toEqual([
        'left' => 'center',
        'top' => 30,
        'bottom' => 80,
    ]);
});

it('applies width and height', function () {
    expect(Matrix::make()->width('90%')->height(400)->toArray())->toEqual([
        'width' => '90%',
        'height' => 400,
    ]);
});

it('sets backgroundColor as backgroundStyle.color, accepting a string or a Filament palette', function () {
    expect(Matrix::make()->backgroundColor('#f5f5f5')->toArray())->toEqual([
        'backgroundStyle' => ['color' => '#f5f5f5'],
    ]);

    expect(Matrix::make()->backgroundColor([500 => 'base'])->toArray())->toEqual([
        'backgroundStyle' => ['color' => 'base'],
    ]);

    expect(Matrix::make()->backgroundColor(Color::Amber)->toArray())->toEqual([
        'backgroundStyle' => ['color' => Color::convertToRgb(Color::Amber[500])],
    ]);
});

it('combines x, y, corner, body, layout and backgroundColor together', function () {
    expect(
        Matrix::make()
            ->x(MatrixDimension::make()->data(['Q1', 'Q2'])->levelSize(40))
            ->y(MatrixDimension::make()->data(['2024', '2025'])->levelSize(70))
            ->corner(['data' => [['coord' => [-1, -1], 'value' => 'Time']]])
            ->body(['data' => [['coord' => [0, 0], 'value' => 'X']]])
            ->top(30)
            ->left('center')
            ->width('90%')
            ->backgroundColor('#fff')
            ->toArray()
    )->toEqual([
        'x' => ['data' => ['Q1', 'Q2'], 'levelSize' => 40],
        'y' => ['data' => ['2024', '2025'], 'levelSize' => 70],
        'corner' => ['data' => [['coord' => [-1, -1], 'value' => 'Time']]],
        'body' => ['data' => [['coord' => [0, 0], 'value' => 'X']]],
        'backgroundStyle' => ['color' => '#fff'],
        'left' => 'center',
        'top' => 30,
        'width' => '90%',
    ]);
});

it('lets raw() override any typed key on the matrix, including nested backgroundStyle', function () {
    expect(
        Matrix::make()->backgroundColor('#fff')->raw(['backgroundStyle' => ['borderColor' => '#000']])->toArray()
    )->toEqual([
        'backgroundStyle' => ['color' => '#fff', 'borderColor' => '#000'],
    ]);
});
