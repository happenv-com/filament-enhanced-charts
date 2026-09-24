<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Option\Series;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLayout;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLineStyle;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLinkedNodes;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRadius;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasSize;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

/**
 * A chord diagram (ECharts 6): nodes on a ring, joined by ribbons whose width
 * is the link value. Like a sankey, the nodes are derived from the links.
 */
final class ChordSeries extends Series
{
    use HasLayout;
    use HasLineStyle;
    use HasLinkedNodes;
    use HasRadius;
    use HasSize;

    private ?bool $clockwise = null;

    private int | float | null $startAngle = null;

    private int | float | string | null $endAngle = null;

    private int | float | null $padAngle = null;

    private int | float | null $minAngle = null;

    /** @var array<string, mixed>|null */
    private ?array $edgeLabel = null;

    protected function type(): string
    {
        return 'chord';
    }

    /** Whether the nodes run clockwise around the ring (the default) or counter-clockwise. */
    public function clockwise(bool $clockwise = true): static
    {
        $this->clockwise = $clockwise;

        return $this;
    }

    /** The angle, in degrees, where the first node starts (90 is the top). */
    public function startAngle(int | float $startAngle): static
    {
        $this->startAngle = $startAngle;

        return $this;
    }

    /** The angle, in degrees, where the ring ends — or `'auto'` for a full circle. */
    public function endAngle(int | float | string $endAngle): static
    {
        $this->endAngle = $endAngle;

        return $this;
    }

    /** The gap between two neighbouring nodes, in degrees. */
    public function padAngle(int | float $padAngle): static
    {
        $this->padAngle = $padAngle;

        return $this;
    }

    /** The smallest angle a node gets, in degrees, so tiny nodes stay visible. */
    public function minAngle(int | float $minAngle): static
    {
        $this->minAngle = $minAngle;

        return $this;
    }

    /** @param Label|array<string, mixed> $edgeLabel Label shown on a ribbon (link) between two nodes. */
    public function edgeLabel(Label | array $edgeLabel): static
    {
        $this->edgeLabel = Normalize::arr($edgeLabel);

        return $this;
    }

    #[\Override]
    protected function build(): array
    {
        $series = array_merge(parent::build(), $this->linkedNodesArray(), $this->radiusLayout());

        if ($this->clockwise !== null) {
            $series['clockwise'] = $this->clockwise;
        }
        if ($this->startAngle !== null) {
            $series['startAngle'] = $this->startAngle;
        }
        if ($this->endAngle !== null) {
            $series['endAngle'] = $this->endAngle;
        }
        if ($this->padAngle !== null) {
            $series['padAngle'] = $this->padAngle;
        }
        if ($this->minAngle !== null) {
            $series['minAngle'] = $this->minAngle;
        }
        $series = array_merge($series, $this->lineStyleArray());
        if ($this->edgeLabel !== null) {
            $series['edgeLabel'] = $this->edgeLabel;
        }
        $series = array_merge($series, $this->sizeArray());

        return array_merge($series, $this->boxLayoutArray());
    }
}
