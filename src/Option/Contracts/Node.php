<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Option\Contracts;

use Illuminate\Contracts\Support\Arrayable;

/** @extends Arrayable<string, mixed> */
interface Node extends Arrayable
{
    /** @return array<string, mixed> */
    public function toArray(): array;
}
