<?php

namespace Happenv\FilamentEnhancedCharts\Option\Concerns;

use Happenv\FilamentEnhancedCharts\Option\Style\LineStyle;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

/**
 * The `lineStyle` sub-config shared by series that draw lines/edges (line,
 * lines, parallel, radar, sankey, tree, graph). What the style paints depends
 * on the series: the polyline itself, or the links/edges between nodes.
 */
trait HasLineStyle
{
    /** @var array<string, mixed>|null */
    private ?array $lineStyle = null;

    /** @param LineStyle|array<string, mixed> $lineStyle */
    public function lineStyle(LineStyle | array $lineStyle): static
    {
        $this->lineStyle = Normalize::arr($lineStyle);

        return $this;
    }

    /**
     * The set lineStyle key, omitting it when unset.
     *
     * @return array<string, mixed>
     */
    protected function lineStyleArray(): array
    {
        return $this->lineStyle !== null ? ['lineStyle' => $this->lineStyle] : [];
    }
}
