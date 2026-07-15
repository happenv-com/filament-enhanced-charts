<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component\Toolbox;

/**
 * Toolbox "brush" feature: adds brush-selection tool icons (rect/polygon
 * select, clear) alongside the `Brush` component's own toolbar.
 */
final class Brush extends ToolboxFeature
{
    public static function make(): self
    {
        return new self;
    }

    public function featureKey(): string
    {
        return 'brush';
    }

    /** @param list<string> $type The enabled brush tools, e.g. `['rect', 'polygon', 'clear']`. */
    public function type(array $type): self
    {
        $this->properties['type'] = $type;

        return $this;
    }
}
