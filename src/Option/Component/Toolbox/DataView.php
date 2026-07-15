<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component\Toolbox;

use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

/**
 * Toolbox "data view" feature: shows the underlying data in an editable
 * table overlay.
 */
final class DataView extends ToolboxFeature
{
    public static function make(): self
    {
        return new self;
    }

    public function featureKey(): string
    {
        return 'dataView';
    }

    public function readOnly(bool $readOnly = true): self
    {
        $this->properties['readOnly'] = $readOnly;

        return $this;
    }

    /** @param array<string, string> $lang Overlay chrome labels, e.g. `['data view', 'turn off', 'refresh']`. */
    public function lang(array $lang): self
    {
        $this->properties['lang'] = $lang;

        return $this;
    }

    /** @param string|array<mixed> $color A CSS color, or a Filament palette (Color::Amber). */
    public function backgroundColor(string | array $color): self
    {
        $this->properties['backgroundColor'] = Normalize::color($color);

        return $this;
    }
}
