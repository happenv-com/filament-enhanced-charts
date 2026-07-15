<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Option\Series;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasData;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLineStyle;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasSymbol;
use Happenv\FilamentEnhancedCharts\Option\Style\AreaStyle;
use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

final class RadarSeries extends Series
{
    use HasData;
    use HasLineStyle;
    use HasSymbol;

    private ?int $radarIndex = null;

    private AreaStyle | array | bool | null $areaStyle = null;

    protected function type(): string
    {
        return 'radar';
    }

    /** Binds this series to a specific `Radar` component by its index in `Option::radar()`, for multi-radar charts. */
    public function radarIndex(int $radarIndex): static
    {
        $this->radarIndex = $radarIndex;

        return $this;
    }

    /**
     * Fills the polygon enclosed by the radar line. Pass `true` for the
     * default fill, `false` to remove it, or an `AreaStyle` builder / array
     * to configure it.
     *
     * @param  AreaStyle|array<string, mixed>|bool  $areaStyle
     */
    public function areaStyle(AreaStyle | array | bool $areaStyle = true): static
    {
        $this->areaStyle = $areaStyle;

        return $this;
    }

    #[\Override]
    protected function build(): array
    {
        $series = array_merge(parent::build(), $this->dataArray(), $this->symbolConfig());

        if ($this->radarIndex !== null) {
            $series['radarIndex'] = $this->radarIndex;
        }
        $series = array_merge($series, $this->lineStyleArray());
        if ($this->areaStyle !== null && $this->areaStyle !== false) {
            $arr = is_bool($this->areaStyle) ? [] : Normalize::arr($this->areaStyle);
            $series['areaStyle'] = $arr === [] ? (object) [] : $arr;
        }

        return $series;
    }
}
