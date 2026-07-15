<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component\Toolbox;

use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

/**
 * Toolbox "save as image" feature: exports the chart canvas as a downloadable
 * PNG/JPG/SVG.
 */
final class SaveAsImage extends ToolboxFeature
{
    public static function make(): self
    {
        return new self;
    }

    public function featureKey(): string
    {
        return 'saveAsImage';
    }

    /** The exported image format: `png`, `jpg`, or `svg`. */
    public function type(string $type): self
    {
        $this->properties['type'] = $type;

        return $this;
    }

    /** The downloaded file name (without extension). */
    public function name(string $name): self
    {
        $this->properties['name'] = $name;

        return $this;
    }

    /** @param string|array<mixed> $color A CSS color, or a Filament palette (Color::Amber). */
    public function backgroundColor(string | array $color): self
    {
        $this->properties['backgroundColor'] = Normalize::color($color);

        return $this;
    }

    public function pixelRatio(int $pixelRatio): self
    {
        $this->properties['pixelRatio'] = $pixelRatio;

        return $this;
    }
}
