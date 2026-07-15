<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Option\Component\Geo;
use Happenv\FilamentEnhancedCharts\Option\Style\Emphasis;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;

covers(Geo::class);

it('builds a geo component with a map name', function () {
    expect(Geo::make()->map('world')->toArray())->toBe(['map' => 'world']);
});

it('accepts roam as a boolean or a gesture string', function () {
    expect(Geo::make()->map('world')->roam()->toArray()['roam'])->toBeTrue();
    expect(Geo::make()->map('world')->roam('move')->toArray()['roam'])->toBe('move');
});

it('applies zoom and center', function () {
    expect(Geo::make()->map('world')->zoom(2)->center([104.0, 37.5])->toArray())
        ->toBe(['map' => 'world', 'zoom' => 2, 'center' => [104.0, 37.5]]);
});

it('accepts label, itemStyle and emphasis as builders or arrays', function () {
    $viaBuilder = Geo::make()
        ->map('world')
        ->label(Label::make()->show())
        ->itemStyle(ItemStyle::make()->color('#c23531'))
        ->emphasis(Emphasis::make()->itemStyle(ItemStyle::make()->color('#e57373')))
        ->toArray();

    $viaArray = Geo::make()
        ->map('world')
        ->label(['show' => true])
        ->itemStyle(['color' => '#c23531'])
        ->emphasis(['itemStyle' => ['color' => '#e57373']])
        ->toArray();

    expect($viaBuilder)->toBe([
        'map' => 'world',
        'label' => ['show' => true],
        'itemStyle' => ['color' => '#c23531'],
        'emphasis' => ['itemStyle' => ['color' => '#e57373']],
    ]);
    expect($viaArray)->toBe($viaBuilder);
});

it('normalizes a builder nested inside select()', function () {
    expect(
        Geo::make()->map('world')->select(['itemStyle' => ItemStyle::make()->color('#c23531')])->toArray()['select']
    )->toBe(['itemStyle' => ['color' => '#c23531']]);
});

it('applies nameProperty', function () {
    expect(Geo::make()->map('world')->nameProperty('NAME')->toArray()['nameProperty'])->toBe('NAME');
});

it('applies box layout edges', function () {
    expect(Geo::make()->map('world')->top(10)->left('5%')->right(20)->bottom('10%')->toArray())
        ->toBe(['map' => 'world', 'left' => '5%', 'right' => 20, 'top' => 10, 'bottom' => '10%']);
});

it('applies layoutCenter and layoutSize', function () {
    expect(Geo::make()->map('world')->layoutCenter(['50%', '50%'])->layoutSize('100%')->toArray())
        ->toBe(['map' => 'world', 'layoutCenter' => ['50%', '50%'], 'layoutSize' => '100%']);
});

it('normalizes builders nested inside regions()', function () {
    expect(
        Geo::make()
            ->map('world')
            ->regions([
                ['name' => 'France', 'itemStyle' => ItemStyle::make()->color('#c23531')],
                ['name' => 'Germany', 'selected' => true],
            ])
            ->toArray()['regions']
    )->toBe([
        ['name' => 'France', 'itemStyle' => ['color' => '#c23531']],
        ['name' => 'Germany', 'selected' => true],
    ]);
});

it('lets raw() override a geo map name', function () {
    expect(Geo::make()->map('world')->raw(['map' => 'USA'])->toArray()['map'])->toBe('USA');
});

it('defaults silent() to true', function () {
    expect(Geo::make()->map('world')->silent()->toArray())
        ->toEqual(['map' => 'world', 'silent' => true]);

    expect(Geo::make()->map('world')->silent(false)->toArray())
        ->toEqual(['map' => 'world', 'silent' => false]);
});
