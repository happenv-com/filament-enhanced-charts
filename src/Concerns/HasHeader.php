<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Concerns;

use BackedEnum;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;

trait HasHeader
{
    protected static ?string $heading = null;

    protected static ?string $subheading = null;

    protected static string | BackedEnum | null $headingIcon = null;

    protected static bool $isCollapsible = false;

    /**
     * Retrieves the heading used in the class.
     */
    protected function getHeading(): null | string | Htmlable | View
    {
        return static::$heading;
    }

    /**
     * Retrieves the subheading used in the class.
     */
    protected function getSubheading(): null | string | Htmlable | View
    {
        return static::$subheading;
    }

    /**
     * Retrieves the icon shown beside the heading, the same slot a Filament section's
     * `icon()` fills.
     */
    protected function getHeadingIcon(): string | BackedEnum | Htmlable | null
    {
        return static::$headingIcon;
    }

    /**
     * Retrieves content placed at the end of the header row, before the filters — a status
     * badge, a link. Rendered in the section's `afterHeader` slot.
     */
    protected function getAfterHeader(): null | Htmlable | View
    {
        return null;
    }

    /**
     * Retrieves the collapsible state of the header.
     */
    protected function isCollapsible(): bool
    {
        return static::$isCollapsible;
    }
}
