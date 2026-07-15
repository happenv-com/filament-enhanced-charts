<?php

use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Enums\TooltipTrigger;
use Happenv\FilamentEnhancedCharts\Option\Component\Tooltip;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;

it('sets show and trigger from the enum', function () {
    expect(Tooltip::make()->show(false)->trigger(TooltipTrigger::Axis)->toArray())
        ->toEqual(['show' => false, 'trigger' => 'axis']);
});

it('sets a formatter string and a RawJs value formatter', function () {
    expect(Tooltip::make()->formatter('{b}: {c}')->valueFormatter(RawJs::make('(value) => value'))->toArray())
        ->toEqual([
            'formatter' => '{b}: {c}',
            'valueFormatter' => ['__js__' => '(value) => value'],
        ]);
});

it('sets an axis pointer config', function () {
    expect(Tooltip::make()->axisPointer(['type' => 'shadow'])->toArray())
        ->toEqual(['axisPointer' => ['type' => 'shadow']]);
});

it('sets background/border color, width and text style', function () {
    expect(
        Tooltip::make()
            ->backgroundColor('#fff')
            ->borderColor('#eee')
            ->borderWidth(1)
            ->textStyle(Label::make()->color('#333')->fontSize(12))
            ->toArray()
    )->toEqual([
        'backgroundColor' => '#fff',
        'borderColor' => '#eee',
        'borderWidth' => 1,
        'textStyle' => ['color' => '#333', 'fontSize' => 12],
    ]);
});

it('accepts a plain array for text style', function () {
    expect(Tooltip::make()->textStyle(['fontSize' => 14])->toArray())
        ->toEqual(['textStyle' => ['fontSize' => 14]]);
});

it('sets position as a string, an array and a RawJs callback', function () {
    expect(Tooltip::make()->position('top')->toArray())
        ->toEqual(['position' => 'top']);

    expect(Tooltip::make()->position(['top' => 10, 'left' => '10%'])->toArray())
        ->toEqual(['position' => ['top' => 10, 'left' => '10%']]);

    expect(Tooltip::make()->position(RawJs::make('(point) => point'))->toArray())
        ->toEqual(['position' => ['__js__' => '(point) => point']]);
});

it('sets confine, appendTo, enterable, padding, extraCssText and order', function () {
    expect(
        Tooltip::make()
            ->confine(true)
            ->appendTo('body')
            ->enterable(true)
            ->padding(8)
            ->extraCssText('box-shadow: none;')
            ->order('valueDesc')
            ->toArray()
    )->toEqual([
        'confine' => true,
        'appendTo' => 'body',
        'enterable' => true,
        'padding' => 8,
        'extraCssText' => 'box-shadow: none;',
        'order' => 'valueDesc',
    ]);
});

it('does not emit keys for unset setters', function () {
    expect(Tooltip::make()->toArray())->toEqual([]);
});

it('sets showContent and transitionDuration', function () {
    expect(Tooltip::make()->showContent(false)->transitionDuration(0)->toArray())
        ->toEqual(['showContent' => false, 'transitionDuration' => 0]);
});

it('sets borderRadius as a single value or [tl, tr, br, bl]', function () {
    expect(Tooltip::make()->borderRadius(4)->toArray())
        ->toEqual(['borderRadius' => 4]);

    expect(Tooltip::make()->borderRadius([4, 4, 0, 0])->toArray())
        ->toEqual(['borderRadius' => [4, 4, 0, 0]]);
});
