<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Option\Series;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasData;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLineStyle;
use Happenv\FilamentEnhancedCharts\Option\Style\LinesEffect;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

/**
 * Draws lines between coordinates — flight routes, effect lines — on top of
 * `geo` (commonly paired with `geoIndex()`), cartesian, or polar coordinate
 * systems.
 */
final class LinesSeries extends Series
{
    use HasData;
    use HasLineStyle;

    private ?bool $polyline = null;

    /** @var array<string, mixed>|null */
    private ?array $effect = null;

    protected function type(): string
    {
        return 'lines';
    }

    /** Draws each item's coords as straight polyline segments (no curveness, label, or animation). */
    public function polyline(bool $polyline = true): static
    {
        $this->polyline = $polyline;

        return $this;
    }

    /**
     * The moving "flight path" trail animation drawn along each line.
     *
     * @param  LinesEffect|array<string, mixed>  $effect
     */
    public function effect(LinesEffect | array $effect): static
    {
        $this->effect = Normalize::arr($effect);

        return $this;
    }

    #[\Override]
    protected function build(): array
    {
        $series = array_merge(parent::build(), $this->linesDataArray());

        if ($this->polyline !== null) {
            $series['polyline'] = $this->polyline;
        }
        if ($this->effect !== null) {
            $series['effect'] = $this->effect;
        }

        return array_merge($series, $this->lineStyleArray());
    }

    /**
     * The set `data` key, each item normalized to `{coords: [[x,y],...]}`. A
     * bare pair of coordinates (`[[x,y],[x,y]]`) is shorthand for the full
     * item shape and gets wrapped; an item already shaped as `{coords: [...]}`
     * (optionally with name/lineStyle/effect/…) passes through untouched.
     *
     * @return array<string, mixed>
     */
    private function linesDataArray(): array
    {
        $data = $this->dataArray();

        if (! isset($data['data']) || ! is_array($data['data'])) {
            return $data;
        }

        $data['data'] = array_map(
            static fn (mixed $item): mixed => is_array($item) && array_is_list($item) ? ['coords' => $item] : $item,
            $data['data'],
        );

        return $data;
    }
}
