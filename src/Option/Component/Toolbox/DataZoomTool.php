<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component\Toolbox;

use Happenv\FilamentEnhancedCharts\Enums\DataZoomFilterMode;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

/**
 * Toolbox "data zoom" feature: adds zoom/reset icons that drive the same
 * zoom behaviour as the `DataZoom` component, without needing one configured.
 */
final class DataZoomTool extends ToolboxFeature
{
    public static function make(): self
    {
        return new self;
    }

    public function featureKey(): string
    {
        return 'dataZoom';
    }

    /** The x-axis(es) this feature controls: an index, `false` to disable, or `'none'`. */
    public function xAxisIndex(int | bool | string $xAxisIndex): self
    {
        $this->properties['xAxisIndex'] = $xAxisIndex;

        return $this;
    }

    /** The y-axis(es) this feature controls: an index, `false` to disable, or `'none'`. */
    public function yAxisIndex(int | bool | string $yAxisIndex): self
    {
        $this->properties['yAxisIndex'] = $yAxisIndex;

        return $this;
    }

    /** How data is filtered while zoomed: `filter` (default), `weakFilter`, `empty`, or `none`. */
    public function filterMode(DataZoomFilterMode | string $filterMode): self
    {
        $this->properties['filterMode'] = Normalize::enum($filterMode);

        return $this;
    }
}
