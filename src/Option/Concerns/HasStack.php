<?php

namespace Happenv\FilamentEnhancedCharts\Option\Concerns;

/**
 * The `stack` name shared by series that stack their values (line, bar):
 * series sharing a stack name are summed on top of each other.
 */
trait HasStack
{
    private ?string $stack = null;

    /** Stacks this series with every other series sharing the same stack name. */
    public function stack(string $stack): static
    {
        $this->stack = $stack;

        return $this;
    }

    /**
     * The set stack key, omitting it when unset.
     *
     * @return array<string, string>
     */
    protected function stackArray(): array
    {
        return $this->stack !== null ? ['stack' => $this->stack] : [];
    }
}
