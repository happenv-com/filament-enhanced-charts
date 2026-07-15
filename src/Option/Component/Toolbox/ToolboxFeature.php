<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Option\Component\Toolbox;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Illuminate\Support\Traits\Conditionable;

/**
 * Shared `show`/`title` properties for toolbox feature builders (saveAsImage,
 * restore, dataView, dataZoom, magicType, brush). Concrete subclasses add
 * their own config and register under `Toolbox::feature()` via `featureKey()`.
 */
abstract class ToolboxFeature implements Node
{
    use Conditionable;
    use HasRaw;

    /** @var array<string, mixed> */
    protected array $properties = [];

    /** The key this feature is emitted under in `toolbox.feature`, e.g. `saveAsImage`. */
    abstract public function featureKey(): string;

    public function show(bool $show = true): static
    {
        $this->properties['show'] = $show;

        return $this;
    }

    /** @param string|array<string, string> $title A single label, or per-sub-type labels (e.g. `['zoom' => '…', 'back' => '…']`). */
    public function title(string | array $title): static
    {
        $this->properties['title'] = $title;

        return $this;
    }

    public function toArray(): array
    {
        return $this->mergeRaw($this->properties);
    }
}
