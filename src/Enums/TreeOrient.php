<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Enums;

/**
 * Growth direction of an orthogonal tree layout ('LR' left-to-right, 'RL'
 * right-to-left, 'TB' top-to-bottom, 'BT' bottom-to-top). Ignored when the
 * tree uses a radial layout.
 */
enum TreeOrient: string
{
    case LeftRight = 'LR';
    case RightLeft = 'RL';
    case TopBottom = 'TB';
    case BottomTop = 'BT';
}
