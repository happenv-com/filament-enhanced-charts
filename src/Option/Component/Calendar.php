<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component;

use Happenv\FilamentEnhancedCharts\Enums\Orient;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLayout;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Illuminate\Support\Traits\Conditionable;

/**
 * A `calendar` coordinate component: lays days out on a calendar-shaped
 * coordinate system. A heatmap/scatter/graph series binds to it via the base
 * `Series::coordinateSystem('calendar')` + `calendarIndex()` — this component
 * only positions and styles the grid itself.
 */
final class Calendar implements Node
{
    use Conditionable;
    use HasLayout;
    use HasRaw;

    private string | int | null $range = null;

    /** @var list<string>|null */
    private ?array $rangeList = null;

    private int | string | null $cellSize = null;

    /** @var list<int|string>|null */
    private ?array $cellSizeList = null;

    private ?string $orient = null;

    /** @var array<string, mixed>|null */
    private ?array $dayLabel = null;

    /** @var array<string, mixed>|null */
    private ?array $monthLabel = null;

    /** @var array<string, mixed>|null */
    private ?array $yearLabel = null;

    /** @var array<string, mixed>|null */
    private ?array $splitLine = null;

    /** @var array<string, mixed>|null */
    private ?array $itemStyle = null;

    public static function make(): self
    {
        return new self;
    }

    /**
     * The time span rendered: a single year (`'2017'`), a single month
     * (`'2017-02'`), or a `[start, end]` date range.
     *
     * @param  string|int|list<string>  $range
     */
    public function range(string | int | array $range): self
    {
        if (is_array($range)) {
            $this->rangeList = $range;
            $this->range = null;
        } else {
            $this->range = $range;
            $this->rangeList = null;
        }

        return $this;
    }

    /**
     * The size of each day cell: a single value/`'auto'`, or `[width, height]`
     * (either of which may itself be `'auto'`).
     *
     * @param  int|string|list<int|string>  $size
     */
    public function cellSize(int | string | array $size): self
    {
        if (is_array($size)) {
            $this->cellSizeList = $size;
            $this->cellSize = null;
        } else {
            $this->cellSize = $size;
            $this->cellSizeList = null;
        }

        return $this;
    }

    public function orient(Orient | string $orient): self
    {
        $this->orient = Normalize::enum($orient);

        return $this;
    }

    /** @param  array<string, mixed>  $config Label options plus `firstDay`, `margin`, `position` ('start'/'end'), `nameMap`. */
    public function dayLabel(array $config): self
    {
        $this->dayLabel = $config;

        return $this;
    }

    /** @param  array<string, mixed>  $config Label options plus `margin`, `position` ('start'/'end'), `nameMap`, `formatter`. */
    public function monthLabel(array $config): self
    {
        $this->monthLabel = $config;

        return $this;
    }

    /** @param  array<string, mixed>  $config Label options plus `margin`, `position` ('top'/'bottom'/'left'/'right'), `formatter`. */
    public function yearLabel(array $config): self
    {
        $this->yearLabel = $config;

        return $this;
    }

    /** @param  bool|array<string, mixed>  $splitLine `true`/`false` toggles visibility; an array configures `show`/`lineStyle` directly. */
    public function splitLine(bool | array $splitLine): self
    {
        $this->splitLine = is_bool($splitLine) ? ['show' => $splitLine] : $splitLine;

        return $this;
    }

    /** @param  ItemStyle|array<string, mixed>  $itemStyle */
    public function itemStyle(ItemStyle | array $itemStyle): self
    {
        $this->itemStyle = Normalize::arr($itemStyle);

        return $this;
    }

    public function toArray(): array
    {
        $calendar = [];

        if ($this->rangeList !== null) {
            $calendar['range'] = $this->rangeList;
        } elseif ($this->range !== null) {
            $calendar['range'] = $this->range;
        }

        if ($this->cellSizeList !== null) {
            $calendar['cellSize'] = $this->cellSizeList;
        } elseif ($this->cellSize !== null) {
            $calendar['cellSize'] = $this->cellSize;
        }

        if ($this->orient !== null) {
            $calendar['orient'] = $this->orient;
        }
        if ($this->dayLabel !== null) {
            $calendar['dayLabel'] = $this->dayLabel;
        }
        if ($this->monthLabel !== null) {
            $calendar['monthLabel'] = $this->monthLabel;
        }
        if ($this->yearLabel !== null) {
            $calendar['yearLabel'] = $this->yearLabel;
        }
        if ($this->splitLine !== null) {
            $calendar['splitLine'] = $this->splitLine;
        }
        if ($this->itemStyle !== null) {
            $calendar['itemStyle'] = $this->itemStyle;
        }

        $calendar = array_merge($calendar, $this->boxLayoutArray());

        return $this->mergeRaw($calendar);
    }
}
