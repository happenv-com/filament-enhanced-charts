<?php

namespace Happenv\FilamentEnhancedCharts\Option\Series;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasData;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLineStyle;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasStack;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasSymbol;
use Happenv\FilamentEnhancedCharts\Option\Style\AreaStyle;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

final class LineSeries extends Series
{
    use HasData;
    use HasLineStyle;
    use HasStack;
    use HasSymbol;

    private bool | int | float | null $smooth = null;

    private AreaStyle | array | bool | null $area = null;

    private bool $dashed = false;

    private ?bool $showSymbol = null;

    private bool | string | null $showAllSymbol = null;

    private bool | string | null $step = null;

    protected function type(): string
    {
        return 'line';
    }

    /**
     * Smooths the line into a spline curve instead of straight segments
     * between points. Pass `true`/`false`, or a float `0..1` for the curve
     * smoothness (0 = straight, 1 = maximally curved).
     */
    public function smooth(bool | int | float $smooth = true): static
    {
        $this->smooth = $smooth;

        return $this;
    }

    /** @param  bool|string  $step  Renders a step line instead of straight segments: `true`/`'start'`/`'middle'`/`'end'`. */
    public function step(bool | string $step): static
    {
        $this->step = $step;

        return $this;
    }

    /** @param  bool|string  $showAllSymbol  Whether every data-point symbol is shown, even when they'd overlap: `true`/`false`/`'auto'`. */
    public function showAllSymbol(bool | string $showAllSymbol = true): static
    {
        $this->showAllSymbol = $showAllSymbol;

        return $this;
    }

    /**
     * Dashes the line. A modifier layered over `lineStyle()` — it survives a
     * later `lineStyle()` call regardless of order (both set the line's style,
     * and `dashed()` always wins on the `type` key at build time).
     */
    public function dashed(): static
    {
        $this->dashed = true;

        return $this;
    }

    public function area(AreaStyle | bool $area = true): static
    {
        $this->area = $area;

        return $this;
    }

    /**
     * The ECharts-named equivalent of `area()`: fills the region under the
     * line. Pass `true` for the default fill, `false` to remove it, or an
     * `AreaStyle` builder / array to configure it.
     *
     * @param  AreaStyle|array<string, mixed>|bool  $areaStyle
     */
    public function areaStyle(AreaStyle | array | bool $areaStyle = true): static
    {
        $this->area = $areaStyle;

        return $this;
    }

    /** Show/hide the data-point symbols (the line itself stays). */
    public function showSymbol(bool $showSymbol = true): static
    {
        $this->showSymbol = $showSymbol;

        return $this;
    }

    #[\Override]
    protected function build(): array
    {
        $series = array_merge(parent::build(), $this->dataArray(), $this->symbolConfig());

        if ($this->showSymbol !== null) {
            $series['showSymbol'] = $this->showSymbol;
        }
        if ($this->showAllSymbol !== null) {
            $series['showAllSymbol'] = $this->showAllSymbol;
        }
        if ($this->step !== null) {
            $series['step'] = $this->step;
        }
        if ($this->smooth !== null) {
            $series['smooth'] = $this->smooth;
        }

        $lineStyle = $this->lineStyleArray();
        if ($this->dashed) {
            $lineStyle['lineStyle'] = array_merge($lineStyle['lineStyle'] ?? [], ['type' => 'dashed']);
        }
        $series = array_merge($series, $lineStyle);

        if ($this->area !== null && $this->area !== false) {
            $arr = is_bool($this->area) ? [] : Normalize::arr($this->area);
            $series['areaStyle'] = $arr === [] ? (object) [] : $arr;
        }

        return array_merge($series, $this->stackArray());
    }
}
