<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component\Toolbox;

/**
 * Toolbox "magic type" feature: lets the viewer switch the series' chart
 * type on the fly (e.g. line/bar/stack).
 */
final class MagicType extends ToolboxFeature
{
    /** @param list<string> $types The switchable chart types, e.g. `['line', 'bar', 'stack']`. */
    public static function make(array $types): self
    {
        $feature = new self;
        $feature->properties['type'] = $types;

        return $feature;
    }

    public function featureKey(): string
    {
        return 'magicType';
    }
}
