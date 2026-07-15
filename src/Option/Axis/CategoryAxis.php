<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Option\Axis;

use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

final class CategoryAxis extends Axis
{
    /** @var array<mixed>|null */
    private ?array $data = null;

    protected function type(): string
    {
        return 'category';
    }

    /**
     * The axis categories, as a positional list. A keyed Collection has its
     * keys dropped — categories are positional.
     *
     * @param  iterable<mixed>  $data
     */
    public function data(iterable $data): static
    {
        $this->data = Normalize::list($data);

        return $this;
    }

    #[\Override]
    protected function build(): array
    {
        $axis = parent::build();

        if ($this->data !== null) {
            $axis['data'] = Normalize::value($this->data);
        }

        return $axis;
    }
}
