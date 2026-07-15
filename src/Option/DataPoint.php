<?php

namespace Happenv\FilamentEnhancedCharts\Option;

use BcMath\Number;
use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Enums\Symbol;
use Happenv\FilamentEnhancedCharts\Option\Component\Tooltip;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Happenv\FilamentEnhancedCharts\Option\Style\Emphasis;
use Happenv\FilamentEnhancedCharts\Option\Style\ItemStyle;
use Happenv\FilamentEnhancedCharts\Option\Style\Label;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;
use Illuminate\Support\Traits\Conditionable;

final class DataPoint implements Node
{
    use Conditionable;
    use HasRaw;

    private ?string $name = null;

    /** @var array<string, mixed>|null */
    private ?array $itemStyle = null;

    /** @var array<string, mixed>|null */
    private ?array $label = null;

    private ?bool $selected = null;

    private ?string $symbol = null;

    /** @var int|array<int, int|float>|array{__js__: string}|null */
    private int | float | array | null $symbolSize = null;

    /** @var array<string, mixed>|null */
    private ?array $emphasis = null;

    /** @var array<string, mixed>|null */
    private ?array $tooltip = null;

    /** @var array<string, mixed>|null */
    private ?array $title = null;

    /** @var array<string, mixed>|null */
    private ?array $detail = null;

    private ?string $groupId = null;

    /** @var array<string, mixed>|null */
    private ?array $labelLine = null;

    private function __construct(private readonly int | float | string | Number | null $value) {}

    public static function make(int | float | string | Number | null $value): self
    {
        return new self($value);
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /** @param ItemStyle|array<string, mixed> $itemStyle */
    public function itemStyle(ItemStyle | array $itemStyle): self
    {
        $this->itemStyle = Normalize::arr($itemStyle);

        return $this;
    }

    /** @param Label|array<string, mixed> $label */
    public function label(Label | array $label): self
    {
        $this->label = Normalize::arr($label);

        return $this;
    }

    /** Whether this point starts out selected (paired with the series' `selectedMode()`). */
    public function selected(bool $selected = true): self
    {
        $this->selected = $selected;

        return $this;
    }

    public function symbol(Symbol | string $symbol): self
    {
        $this->symbol = Normalize::enum($symbol);

        return $this;
    }

    /** @param  int|float|array<int, int|float>|RawJs  $symbolSize  A fixed size, a [width, height] pair, or a RawJs callback. */
    public function symbolSize(int | float | array | RawJs $symbolSize): self
    {
        $this->symbolSize = $symbolSize instanceof RawJs ? Normalize::value($symbolSize) : $symbolSize;

        return $this;
    }

    /**
     * The hover/emphasis state for this point. Pass `false` to disable it
     * entirely, or an `Emphasis` builder / array to configure it.
     *
     * @param  Emphasis|array<string, mixed>|bool  $emphasis
     */
    public function emphasis(Emphasis | array | bool $emphasis = true): self
    {
        $this->emphasis = is_bool($emphasis)
            ? ['disabled' => ! $emphasis]
            : Normalize::arr($emphasis);

        return $this;
    }

    /** @param Tooltip|array<string, mixed> $tooltip */
    public function tooltip(Tooltip | array $tooltip): self
    {
        $this->tooltip = Normalize::arr($tooltip);

        return $this;
    }

    /**
     * Per-datapoint override of the gauge series' internal `title` block
     * (only meaningful on a multi-data gauge, one dial per point).
     *
     * @param  array<string, mixed>  $title
     */
    public function title(array $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Per-datapoint override of the gauge series' `detail` block (only
     * meaningful on a multi-data gauge, one dial per point).
     *
     * @param  array<string, mixed>  $detail
     */
    public function detail(array $detail): self
    {
        $this->detail = $detail;

        return $this;
    }

    /** Groups this point with others sharing the same id for shared transition animations. */
    public function groupId(string $groupId): self
    {
        $this->groupId = $groupId;

        return $this;
    }

    /** @param  array<string, mixed>|bool  $labelLine  `false` hides the leader line; an array configures it directly. */
    public function labelLine(array | bool $labelLine = true): self
    {
        $this->labelLine = is_bool($labelLine) ? ['show' => $labelLine] : $labelLine;

        return $this;
    }

    public function toArray(): array
    {
        // `value` is always emitted (a point may legitimately be null); the
        // rest emit only when set.
        return $this->mergeRaw(
            ['value' => Normalize::value($this->value)] + Normalize::filled([
                'name' => $this->name,
                'itemStyle' => $this->itemStyle,
                'label' => $this->label,
                'selected' => $this->selected,
                'symbol' => $this->symbol,
                'symbolSize' => $this->symbolSize,
                'emphasis' => $this->emphasis,
                'tooltip' => $this->tooltip,
                'title' => $this->title,
                'detail' => $this->detail,
                'groupId' => $this->groupId,
                'labelLine' => $this->labelLine,
            ]),
        );
    }
}
