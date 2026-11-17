<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Option\Concerns;

use Happenv\FilamentEnhancedCharts\Support\NumberFormat;

/**
 * How a value axis writes its tick labels: `->format(NumberFormat::currency('PLN'))`.
 * A formatter set through `axisLabel()` wins over it.
 */
trait HasNumberFormat
{
    private ?NumberFormat $numberFormat = null;

    /** Write the tick labels in this number format (the locale of the application unless the format pins one). */
    public function format(NumberFormat $format): static
    {
        $this->numberFormat = $format;

        return $this;
    }

    /**
     * The built axis with the number format laid into its `axisLabel`, unless a formatter is already there.
     *
     * @param  array<string, mixed>  $axis
     * @return array<string, mixed>
     */
    protected function withNumberFormat(array $axis): array
    {
        if ($this->numberFormat instanceof NumberFormat && ! isset($axis['axisLabel']['formatter'])) {
            $axis['axisLabel']['formatter'] = $this->numberFormat->marker('axis');
        }

        return $axis;
    }
}
