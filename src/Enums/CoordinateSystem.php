<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Enums;

/**
 * The coordinate system a series is drawn on. Not every series accepts every
 * case — consult the series' own `coordinateSystem()` doc for which apply
 * (e.g. `LinesSeries` commonly pairs `Geo` with `geoIndex()`, or `Cartesian2d`
 * with the default xAxis/yAxis).
 */
enum CoordinateSystem: string
{
    case Cartesian2d = 'cartesian2d';
    case Geo = 'geo';
    case Polar = 'polar';
    case Calendar = 'calendar';
    case Matrix = 'matrix';
    case SingleAxis = 'singleAxis';
    case None = 'none';
}
