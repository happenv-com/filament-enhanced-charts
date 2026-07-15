<?php

use Happenv\FilamentEnhancedCharts\Option\Component\Title;

covers(Title::class);

it('builds text and subtext via make() and the fluent setters', function () {
    expect(Title::make('Revenue')->subtext('Last 12 months')->toArray())
        ->toEqual(['text' => 'Revenue', 'subtext' => 'Last 12 months']);
});

it('builds link, sublink, target, and subtarget', function () {
    expect(
        Title::make('Revenue')
            ->link('https://example.com')
            ->sublink('https://example.com/details')
            ->target('blank')
            ->subtarget('self')
            ->toArray()
    )->toEqual([
        'text' => 'Revenue',
        'link' => 'https://example.com',
        'sublink' => 'https://example.com/details',
        'target' => 'blank',
        'subtarget' => 'self',
    ]);
});

it('builds itemGap and padding', function () {
    expect(Title::make('Revenue')->itemGap(6)->padding([4, 8])->toArray())
        ->toEqual(['text' => 'Revenue', 'itemGap' => 6, 'padding' => [4, 8]]);
});

it('normalizes backgroundColor and borderColor through the Filament color palette', function () {
    expect(Title::make('Revenue')->backgroundColor('#fff')->borderColor('#eee')->toArray())
        ->toEqual(['text' => 'Revenue', 'backgroundColor' => '#fff', 'borderColor' => '#eee']);
});

it('builds borderWidth and borderRadius', function () {
    expect(Title::make('Revenue')->borderWidth(1.5)->borderRadius([4, 4, 0, 0])->toArray())
        ->toEqual(['text' => 'Revenue', 'borderWidth' => 1.5, 'borderRadius' => [4, 4, 0, 0]]);
});

it('defaults show() to true and builds triggerEvent', function () {
    expect(Title::make('Revenue')->show()->triggerEvent()->toArray())
        ->toEqual(['text' => 'Revenue', 'show' => true, 'triggerEvent' => true]);

    expect(Title::make('Revenue')->show(false)->toArray())
        ->toEqual(['text' => 'Revenue', 'show' => false]);
});

it('combines the shared layout edges from HasLayout', function () {
    expect(Title::make('Revenue')->left('center')->top(10)->toArray())
        ->toEqual(['text' => 'Revenue', 'left' => 'center', 'top' => 10]);
});

it('builds textBaseline', function () {
    expect(Title::make('Revenue')->textBaseline('middle')->toArray())
        ->toEqual(['text' => 'Revenue', 'textBaseline' => 'middle']);
});
