<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Option\Style;

final class AreaStyle extends Style
{
    /** @param string $origin ECharts' fill origin: 'auto', 'start', or 'end'. */
    public function origin(string $origin): static
    {
        $this->properties['origin'] = $origin;

        return $this;
    }

    public function shadowBlur(int | float $blur): static
    {
        $this->properties['shadowBlur'] = $blur;

        return $this;
    }
}
