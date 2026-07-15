<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Concerns;

trait HasRenderer
{
    protected static string $renderer = 'canvas';

    /**
     * Retrieves the value of the static property $renderer.
     *
     * @return string The value of the $renderer property
     */
    protected function getRenderer(): string
    {
        return static::$renderer;
    }
}
