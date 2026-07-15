<?php

namespace Happenv\FilamentEnhancedCharts\Option\Series;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasData;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasSymbol;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

final class EffectScatterSeries extends Series
{
    use HasData;
    use HasSymbol;

    private ?string $effectType = null;

    private ?string $showEffectOn = null;

    /** @var array<string, mixed>|null */
    private ?array $rippleEffect = null;

    protected function type(): string
    {
        return 'effectScatter';
    }

    /** The kind of effect animation. ECharts currently only ships `'ripple'`. */
    public function effectType(string $effectType = 'ripple'): static
    {
        $this->effectType = $effectType;

        return $this;
    }

    /** When to play the effect: `'render'` (always) or `'emphasis'` (on hover). */
    public function showEffectOn(string $showEffectOn): static
    {
        $this->showEffectOn = $showEffectOn;

        return $this;
    }

    /**
     * The ripple animation config: `period`/`scale`/`brushType`/`color`/`number`.
     * No dedicated builder — pass the array directly.
     *
     * @param  array<string, mixed>  $rippleEffect
     */
    public function rippleEffect(array $rippleEffect): static
    {
        $this->rippleEffect = Normalize::value($rippleEffect);

        return $this;
    }

    #[\Override]
    protected function build(): array
    {
        $series = array_merge(parent::build(), $this->dataArray(), $this->symbolConfig());
        if ($this->effectType !== null) {
            $series['effectType'] = $this->effectType;
        }
        if ($this->showEffectOn !== null) {
            $series['showEffectOn'] = $this->showEffectOn;
        }
        if ($this->rippleEffect !== null) {
            $series['rippleEffect'] = $this->rippleEffect;
        }

        return $series;
    }
}
