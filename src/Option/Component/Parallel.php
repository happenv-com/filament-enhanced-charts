<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasLayout;
use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Illuminate\Support\Traits\Conditionable;

final class Parallel implements Node
{
    use Conditionable;
    use HasLayout;
    use HasRaw;

    /** @var array<string, mixed>|null */
    private ?array $parallelAxisDefault = null;

    public static function make(): self
    {
        return new self;
    }

    /** @param array<string, mixed> $config A ParallelAxis-shaped config applied as the default for every axis. */
    public function parallelAxisDefault(array $config): self
    {
        $this->parallelAxisDefault = $config;

        return $this;
    }

    public function toArray(): array
    {
        $parallel = $this->boxLayoutArray();

        if ($this->parallelAxisDefault !== null) {
            $parallel['parallelAxisDefault'] = $this->parallelAxisDefault;
        }

        return $this->mergeRaw($parallel);
    }
}
