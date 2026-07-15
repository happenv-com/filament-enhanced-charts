<?php

namespace Happenv\FilamentEnhancedCharts\Concerns;

use Happenv\FilamentEnhancedCharts\Widgets\EnhancedChartWidget;

/**
 * @mixin EnhancedChartWidget
 */
trait CanDeferLoading
{
    protected static bool $deferLoading = false;

    public bool $readyToLoad = false;

    /**
     * Retrieves the value of the static property $deferLoading.
     *
     * @return bool The value of the static property $deferLoading.
     */
    protected function getDeferLoading(): bool
    {
        return static::$deferLoading;
    }

    /**
     * Loads the widget.
     */
    public function loadWidget(): void
    {
        $this->readyToLoad = true;

        $this->optionsHash = $this->hashOptions($this->resolveOptions());
    }
}
