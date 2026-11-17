<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

/**
 * A value axis as the option emits it with no number format configured: the
 * default locale-aware plain number on its tick labels (application locale `en`).
 *
 * @param  array<string, mixed>  $axis
 * @return array<string, mixed>
 */
function plainValueAxis(array $axis = []): array
{
    return ['type' => 'value', ...$axis, 'axisLabel' => ['formatter' => ['__number__' => ['style' => 'decimal', 'role' => 'axis', 'locale' => 'en']]]];
}
