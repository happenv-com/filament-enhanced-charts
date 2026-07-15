<?php

namespace Happenv\FilamentEnhancedCharts\Option\Series;

use BcMath\Number;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasAxisLabel;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasData;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRadius;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

final class GaugeSeries extends Series
{
    use HasAxisLabel;
    use HasData;
    use HasRadius;

    private int | float | string | Number | null $min = null;

    private int | float | string | Number | null $max = null;

    private int | float | null $startAngle = null;

    private int | float | null $endAngle = null;

    private ?bool $clockwise = null;

    private ?int $splitNumber = null;

    /** @var array<string, mixed>|null */
    private ?array $axisLine = null;

    /** @var array<string, mixed>|null */
    private ?array $axisTick = null;

    /** @var array<string, mixed>|null */
    private ?array $splitLine = null;

    /** @var array<string, mixed>|null */
    private ?array $pointer = null;

    /** @var array<string, mixed>|null */
    private ?array $anchor = null;

    /** @var array<string, mixed>|null */
    private ?array $title = null;

    /** @var array<string, mixed>|null */
    private ?array $progress = null;

    /** @var array<string, mixed>|null */
    private ?array $detail = null;

    protected function type(): string
    {
        return 'gauge';
    }

    public function min(int | float | string | Number $min): static
    {
        $this->min = $min;

        return $this;
    }

    public function max(int | float | string | Number $max): static
    {
        $this->max = $max;

        return $this;
    }

    /** The gauge's start angle in degrees (0 points right/east, angles increase counter-clockwise). */
    public function startAngle(int | float $startAngle): static
    {
        $this->startAngle = $startAngle;

        return $this;
    }

    /** The gauge's end angle in degrees. */
    public function endAngle(int | float $endAngle): static
    {
        $this->endAngle = $endAngle;

        return $this;
    }

    public function clockwise(bool $clockwise = true): static
    {
        $this->clockwise = $clockwise;

        return $this;
    }

    /** The number of splits the axis is divided into. */
    public function splitNumber(int $splitNumber): static
    {
        $this->splitNumber = $splitNumber;

        return $this;
    }

    /**
     * The gauge's colored ring: `show`, `roundCap`, and a `lineStyle`
     * (`width`, and the `color` band list). Pass `false` to hide it, or a
     * full array — the color-band shape (`[[stopFraction, color], ...]`)
     * has no dedicated builder. Replaces any previously set axisLine
     * wholesale; for incremental tweaks use `axisLineWidth()`/`axisLineColor()`
     * instead.
     *
     * @param  array<string, mixed>|bool  $axisLine
     */
    public function axisLine(array | bool $axisLine = true): static
    {
        $this->axisLine = is_bool($axisLine) ? ['show' => $axisLine] : $axisLine;

        return $this;
    }

    /** Sets axisLine.lineStyle.width — the thickness of the gauge's colored ring. Merges into any existing axisLine. */
    public function axisLineWidth(int $width): static
    {
        $this->axisLine['lineStyle']['width'] = $width;

        return $this;
    }

    /**
     * Sets axisLine.lineStyle.color — the gauge's color bands, each a
     * `[stopFraction, color]` pair with ascending stops ending at `1`, e.g.
     * `[[0.3, '#67e0e3'], [0.7, '#37a2da'], [1, '#fd666d']]`. Merges into any
     * existing axisLine.
     *
     * @param  array<int, array{0: int|float|string|Number, 1: string|array<mixed>}>  $bands
     */
    public function axisLineColor(array $bands): static
    {
        $this->axisLine['lineStyle']['color'] = array_map(
            static fn (array $band): array => [Normalize::value($band[0]), Normalize::color($band[1])],
            $bands
        );

        return $this;
    }

    /** @param  array<string, mixed>|bool  $axisTick  `false` hides the axis ticks; an array configures them directly. */
    public function axisTick(array | bool $axisTick): static
    {
        $this->axisTick = is_bool($axisTick) ? ['show' => $axisTick] : $axisTick;

        return $this;
    }

    /** @param  array<string, mixed>|bool  $splitLine  `false` hides the split lines; an array configures them directly. */
    public function splitLine(array | bool $splitLine): static
    {
        $this->splitLine = is_bool($splitLine) ? ['show' => $splitLine] : $splitLine;

        return $this;
    }

    /** @param  array<string, mixed>|bool  $pointer  `false` hides the pointer; an array configures it directly. */
    public function pointer(array | bool $pointer): static
    {
        $this->pointer = is_bool($pointer) ? ['show' => $pointer] : $pointer;

        return $this;
    }

    /** @param  array<string, mixed>|bool  $anchor  `false` hides the center anchor; an array configures it directly. */
    public function anchor(array | bool $anchor): static
    {
        $this->anchor = is_bool($anchor) ? ['show' => $anchor] : $anchor;

        return $this;
    }

    /**
     * The gauge's own internal title block (its per-series name label,
     * positioned inside the dial) — not `Option::title()`.
     *
     * @param  array<string, mixed>  $title
     */
    public function title(array $title): static
    {
        $this->title = $title;

        return $this;
    }

    /** @param array<string, mixed> $progress */
    public function progress(array $progress): static
    {
        $this->progress = $progress;

        return $this;
    }

    /** @param array<string, mixed> $detail */
    public function detail(array $detail): static
    {
        $this->detail = $detail;

        return $this;
    }

    #[\Override]
    protected function build(): array
    {
        $series = array_merge(parent::build(), $this->dataArray());
        if ($this->min !== null) {
            $series['min'] = Normalize::value($this->min);
        }
        if ($this->max !== null) {
            $series['max'] = Normalize::value($this->max);
        }
        if ($this->startAngle !== null) {
            $series['startAngle'] = $this->startAngle;
        }
        if ($this->endAngle !== null) {
            $series['endAngle'] = $this->endAngle;
        }
        if ($this->clockwise !== null) {
            $series['clockwise'] = $this->clockwise;
        }
        if ($this->splitNumber !== null) {
            $series['splitNumber'] = $this->splitNumber;
        }
        if ($this->axisLine !== null) {
            $series['axisLine'] = $this->axisLine;
        }
        if ($this->axisTick !== null) {
            $series['axisTick'] = $this->axisTick;
        }
        if ($this->splitLine !== null) {
            $series['splitLine'] = $this->splitLine;
        }
        $series = array_merge($series, $this->axisLabelArray());

        if ($this->pointer !== null) {
            $series['pointer'] = $this->pointer;
        }
        if ($this->anchor !== null) {
            $series['anchor'] = $this->anchor;
        }
        if ($this->title !== null) {
            $series['title'] = $this->title;
        }
        if ($this->progress !== null) {
            $series['progress'] = $this->progress;
        }
        if ($this->detail !== null) {
            $series['detail'] = $this->detail;
        }

        return array_merge($series, $this->radiusLayout());
    }
}
