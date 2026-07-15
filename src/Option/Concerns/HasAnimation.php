<?php

namespace Happenv\FilamentEnhancedCharts\Option\Concerns;

use Filament\Support\RawJs;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

/**
 * Per-entry animation controls shared by series and `Option` itself: whether
 * to animate at all, the data-count threshold above which ECharts turns
 * animation off automatically, and the initial/update easing & timing.
 */
trait HasAnimation
{
    private ?bool $animation = null;

    private ?int $animationThreshold = null;

    /** @var int|float|array{__js__: string}|null */
    private int | float | array | null $animationDuration = null;

    private ?string $animationEasing = null;

    /** @var int|float|array{__js__: string}|null */
    private int | float | array | null $animationDelay = null;

    /** @var int|float|array{__js__: string}|null */
    private int | float | array | null $animationDurationUpdate = null;

    private ?string $animationEasingUpdate = null;

    /** @var int|float|array{__js__: string}|null */
    private int | float | array | null $animationDelayUpdate = null;

    /** Enables or disables the initial-render + update animations entirely. */
    public function animation(bool $animation = true): static
    {
        $this->animation = $animation;

        return $this;
    }

    /** Above this many data points, ECharts turns animation off automatically. */
    public function animationThreshold(int $animationThreshold): static
    {
        $this->animationThreshold = $animationThreshold;

        return $this;
    }

    /** The initial-render animation duration, in milliseconds. */
    public function animationDuration(int | float | RawJs $animationDuration): static
    {
        $this->animationDuration = Normalize::value($animationDuration);

        return $this;
    }

    /** The initial-render easing curve, e.g. `'cubicOut'`, `'elasticOut'`. */
    public function animationEasing(string $animationEasing): static
    {
        $this->animationEasing = $animationEasing;

        return $this;
    }

    /** The initial-render animation delay, in milliseconds (or a per-index RawJs callback). */
    public function animationDelay(int | float | RawJs $animationDelay): static
    {
        $this->animationDelay = Normalize::value($animationDelay);

        return $this;
    }

    /** The data-update (re-render) animation duration, in milliseconds. */
    public function animationDurationUpdate(int | float | RawJs $animationDurationUpdate): static
    {
        $this->animationDurationUpdate = Normalize::value($animationDurationUpdate);

        return $this;
    }

    /** The data-update (re-render) easing curve. */
    public function animationEasingUpdate(string $animationEasingUpdate): static
    {
        $this->animationEasingUpdate = $animationEasingUpdate;

        return $this;
    }

    /** The data-update (re-render) animation delay, in milliseconds (or a per-index RawJs callback). */
    public function animationDelayUpdate(int | float | RawJs $animationDelayUpdate): static
    {
        $this->animationDelayUpdate = Normalize::value($animationDelayUpdate);

        return $this;
    }

    /**
     * The set animation* keys, omitting unset ones.
     *
     * @return array<string, mixed>
     */
    protected function animationArray(): array
    {
        return array_filter(
            [
                'animation' => $this->animation,
                'animationThreshold' => $this->animationThreshold,
                'animationDuration' => $this->animationDuration,
                'animationEasing' => $this->animationEasing,
                'animationDelay' => $this->animationDelay,
                'animationDurationUpdate' => $this->animationDurationUpdate,
                'animationEasingUpdate' => $this->animationEasingUpdate,
                'animationDelayUpdate' => $this->animationDelayUpdate,
            ],
            static fn (mixed $value): bool => $value !== null,
        );
    }
}
