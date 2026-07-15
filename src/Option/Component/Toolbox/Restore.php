<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component\Toolbox;

/**
 * Toolbox "restore" feature: resets the chart to its initial option state
 * (undoes zoom/select/data-view edits).
 */
final class Restore extends ToolboxFeature
{
    public static function make(): self
    {
        return new self;
    }

    public function featureKey(): string
    {
        return 'restore';
    }
}
