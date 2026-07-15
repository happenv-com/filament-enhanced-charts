<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component\Toolbox;

use Happenv\FilamentEnhancedCharts\Option\Style\LineStyle;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

/**
 * The toolbox "mark" feature — freehand annotation lines the user can draw,
 * undo, and clear on the chart (emitted under `toolbox.feature.mark`).
 */
final class Mark extends ToolboxFeature
{
    public static function make(): self
    {
        return new self;
    }

    public function featureKey(): string
    {
        return 'mark';
    }

    /** @param LineStyle|array<string, mixed> $lineStyle */
    public function lineStyle(LineStyle | array $lineStyle): self
    {
        $this->properties['lineStyle'] = Normalize::arr($lineStyle);

        return $this;
    }
}
