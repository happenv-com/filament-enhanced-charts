<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Concerns\HasHeader;
use Happenv\FilamentEnhancedCharts\Tests\Fixtures\Widgets\HeaderTestWidget;
use Happenv\FilamentEnhancedCharts\Tests\Fixtures\Widgets\PollableTestWidget;

use function Pest\Livewire\livewire;

covers(HasHeader::class);

it('renders the heading icon in the section header', function (): void {
    livewire(HeaderTestWidget::class)
        ->assertOk()
        ->assertSeeHtml('fi-section-header')
        ->assertSee('With header extras')
        // The section draws the icon at its own size, which nothing else in the widget uses.
        ->assertSeeHtml('fi-icon fi-size-lg');
});

it('renders the after-header content at the end of the header row', function (): void {
    livewire(HeaderTestWidget::class)
        ->assertOk()
        ->assertSeeHtml('<span class="after-header-probe">Estimated</span>');
});

it('renders neither when a widget sets neither', function (): void {
    livewire(PollableTestWidget::class)
        ->assertOk()
        ->assertDontSeeHtml('after-header-probe')
        ->assertDontSeeHtml('fi-icon fi-size-lg')
        ->assertDontSeeHtml('fi-section-header-after-ctn');
});
