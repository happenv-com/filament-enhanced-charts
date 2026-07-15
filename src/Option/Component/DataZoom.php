<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component;

use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Enums\DataZoomFilterMode;
use Happenv\FilamentEnhancedCharts\Enums\Orient;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLayout;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Illuminate\Support\Traits\Conditionable;

/**
 * Zoom/pan control over an axis range: a visible draggable `slider`, or an
 * `inside` drag/wheel zoom with no visible handle.
 */
final class DataZoom implements Node
{
    use Conditionable;
    use HasLayout;
    use HasRaw;

    private int | string | null $height = null;

    private int | string | null $width = null;

    private int | float | null $start = null;

    private int | float | null $end = null;

    /** @var list<int>|null */
    private ?array $xAxisIndex = null;

    /** @var list<int>|null */
    private ?array $yAxisIndex = null;

    private ?string $orient = null;

    private ?bool $show = null;

    private ?string $filterMode = null;

    private ?bool $realtime = null;

    private mixed $startValue = null;

    private mixed $endValue = null;

    private int | float | null $minSpan = null;

    private int | float | null $maxSpan = null;

    private mixed $minValueSpan = null;

    private mixed $maxValueSpan = null;

    private ?bool $zoomLock = null;

    private ?int $throttle = null;

    private ?string $handleIcon = null;

    private int | string | null $handleSize = null;

    /** @var array<string, mixed>|null */
    private ?array $dataBackground = null;

    private ?bool $brushSelect = null;

    private bool | string | null $zoomOnMouseWheel = null;

    private bool | string | null $moveOnMouseMove = null;

    private bool | string | null $moveOnMouseWheel = null;

    /** @var string|array{__js__: string}|null */
    private string | array | null $labelFormatter = null;

    private ?int $zlevel = null;

    private ?int $z = null;

    /** @var array<string, mixed>|null */
    private ?array $textStyle = null;

    private bool | string | null $showDataShadow = null;

    private ?bool $showDetail = null;

    private ?string $id = null;

    private function __construct(private readonly string $type) {}

    public static function slider(): self
    {
        return new self('slider');
    }

    public static function inside(): self
    {
        return new self('inside');
    }

    public function start(int | float $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function end(int | float $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function xAxisIndex(int ...$index): self
    {
        $this->xAxisIndex = $index;

        return $this;
    }

    public function yAxisIndex(int ...$index): self
    {
        $this->yAxisIndex = $index;

        return $this;
    }

    public function orient(Orient | string $orient): self
    {
        $this->orient = Normalize::enum($orient);

        return $this;
    }

    public function height(int | string $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function width(int | string $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function show(bool $show = true): self
    {
        $this->show = $show;

        return $this;
    }

    public function filterMode(DataZoomFilterMode | string $filterMode): self
    {
        $this->filterMode = Normalize::enum($filterMode);

        return $this;
    }

    public function realtime(bool $realtime = true): self
    {
        $this->realtime = $realtime;

        return $this;
    }

    public function startValue(mixed $value): self
    {
        $this->startValue = $value;

        return $this;
    }

    public function endValue(mixed $value): self
    {
        $this->endValue = $value;

        return $this;
    }

    public function minSpan(int | float $span): self
    {
        $this->minSpan = $span;

        return $this;
    }

    public function maxSpan(int | float $span): self
    {
        $this->maxSpan = $span;

        return $this;
    }

    public function minValueSpan(mixed $span): self
    {
        $this->minValueSpan = $span;

        return $this;
    }

    public function maxValueSpan(mixed $span): self
    {
        $this->maxValueSpan = $span;

        return $this;
    }

    public function zoomLock(bool $zoomLock = true): self
    {
        $this->zoomLock = $zoomLock;

        return $this;
    }

    public function throttle(int $throttle): self
    {
        $this->throttle = $throttle;

        return $this;
    }

    public function handleIcon(string $handleIcon): self
    {
        $this->handleIcon = $handleIcon;

        return $this;
    }

    public function handleSize(int | string $handleSize): self
    {
        $this->handleSize = $handleSize;

        return $this;
    }

    /** @param array<string, mixed> $config The unzoomed data background `lineStyle`/`areaStyle`. */
    public function dataBackground(array $config): self
    {
        $this->dataBackground = $config;

        return $this;
    }

    public function brushSelect(bool $brushSelect = true): self
    {
        $this->brushSelect = $brushSelect;

        return $this;
    }

    public function zoomOnMouseWheel(bool | string $zoomOnMouseWheel = true): self
    {
        $this->zoomOnMouseWheel = $zoomOnMouseWheel;

        return $this;
    }

    public function moveOnMouseMove(bool | string $moveOnMouseMove = true): self
    {
        $this->moveOnMouseMove = $moveOnMouseMove;

        return $this;
    }

    public function moveOnMouseWheel(bool | string $moveOnMouseWheel = true): self
    {
        $this->moveOnMouseWheel = $moveOnMouseWheel;

        return $this;
    }

    public function labelFormatter(RawJs | string $formatter): self
    {
        $this->labelFormatter = Normalize::formatter($formatter);

        return $this;
    }

    public function zlevel(int $zlevel): self
    {
        $this->zlevel = $zlevel;

        return $this;
    }

    public function z(int $z): self
    {
        $this->z = $z;

        return $this;
    }

    /** @param Label|array<string, mixed> $style Style of the slider's text labels. */
    public function textStyle(Label | array $style): self
    {
        $this->textStyle = Normalize::arr($style);

        return $this;
    }

    /** Show the unzoomed data's shadow behind the slider track: `true`/`false`/`'auto'`. */
    public function showDataShadow(bool | string $showDataShadow = true): self
    {
        $this->showDataShadow = $showDataShadow;

        return $this;
    }

    /** Show the numeric detail of the current zoom range next to the slider handles. */
    public function showDetail(bool $showDetail = true): self
    {
        $this->showDetail = $showDetail;

        return $this;
    }

    /** Names this component so it can be referenced (e.g. from an action). */
    public function id(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function toArray(): array
    {
        $zoom = ['type' => $this->type];

        if ($this->start !== null) {
            $zoom['start'] = $this->start;
        }
        if ($this->end !== null) {
            $zoom['end'] = $this->end;
        }
        if ($this->xAxisIndex !== null) {
            $zoom['xAxisIndex'] = Normalize::oneOrList($this->xAxisIndex);
        }
        if ($this->yAxisIndex !== null) {
            $zoom['yAxisIndex'] = Normalize::oneOrList($this->yAxisIndex);
        }
        if ($this->orient !== null) {
            $zoom['orient'] = $this->orient;
        }
        if ($this->height !== null) {
            $zoom['height'] = $this->height;
        }
        if ($this->width !== null) {
            $zoom['width'] = $this->width;
        }
        if ($this->show !== null) {
            $zoom['show'] = $this->show;
        }
        if ($this->filterMode !== null) {
            $zoom['filterMode'] = $this->filterMode;
        }
        if ($this->realtime !== null) {
            $zoom['realtime'] = $this->realtime;
        }
        if ($this->startValue !== null) {
            $zoom['startValue'] = Normalize::value($this->startValue);
        }
        if ($this->endValue !== null) {
            $zoom['endValue'] = Normalize::value($this->endValue);
        }
        if ($this->minSpan !== null) {
            $zoom['minSpan'] = $this->minSpan;
        }
        if ($this->maxSpan !== null) {
            $zoom['maxSpan'] = $this->maxSpan;
        }
        if ($this->minValueSpan !== null) {
            $zoom['minValueSpan'] = Normalize::value($this->minValueSpan);
        }
        if ($this->maxValueSpan !== null) {
            $zoom['maxValueSpan'] = Normalize::value($this->maxValueSpan);
        }
        if ($this->zoomLock !== null) {
            $zoom['zoomLock'] = $this->zoomLock;
        }
        if ($this->throttle !== null) {
            $zoom['throttle'] = $this->throttle;
        }
        if ($this->handleIcon !== null) {
            $zoom['handleIcon'] = $this->handleIcon;
        }
        if ($this->handleSize !== null) {
            $zoom['handleSize'] = $this->handleSize;
        }
        if ($this->dataBackground !== null) {
            $zoom['dataBackground'] = Normalize::value($this->dataBackground);
        }
        if ($this->brushSelect !== null) {
            $zoom['brushSelect'] = $this->brushSelect;
        }
        if ($this->zoomOnMouseWheel !== null) {
            $zoom['zoomOnMouseWheel'] = $this->zoomOnMouseWheel;
        }
        if ($this->moveOnMouseMove !== null) {
            $zoom['moveOnMouseMove'] = $this->moveOnMouseMove;
        }
        if ($this->moveOnMouseWheel !== null) {
            $zoom['moveOnMouseWheel'] = $this->moveOnMouseWheel;
        }
        if ($this->labelFormatter !== null) {
            $zoom['labelFormatter'] = $this->labelFormatter;
        }
        if ($this->zlevel !== null) {
            $zoom['zlevel'] = $this->zlevel;
        }
        if ($this->z !== null) {
            $zoom['z'] = $this->z;
        }
        if ($this->textStyle !== null) {
            $zoom['textStyle'] = Normalize::value($this->textStyle);
        }
        if ($this->showDataShadow !== null) {
            $zoom['showDataShadow'] = $this->showDataShadow;
        }
        if ($this->showDetail !== null) {
            $zoom['showDetail'] = $this->showDetail;
        }
        if ($this->id !== null) {
            $zoom['id'] = $this->id;
        }

        return $this->mergeRaw(array_merge($zoom, $this->boxLayoutArray()));
    }
}
