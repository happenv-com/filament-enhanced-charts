<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Option\Component\Graphic;

final class GraphicCircle extends GraphicElement
{
    /** @var array<string, mixed> */
    private array $shape = [];

    protected function elementType(): string
    {
        return 'circle';
    }

    /**
     * The circle geometry: `cx`/`cy`/`r`. Repeated calls merge rather than
     * replace.
     *
     * @param  array<string, mixed>  $shape
     */
    public function shape(array $shape): static
    {
        $this->shape = array_merge($this->shape, $shape);

        return $this;
    }

    #[\Override]
    protected function build(): array
    {
        $element = parent::build();
        if ($this->shape !== []) {
            $element['shape'] = $this->shape;
        }

        return $element;
    }
}
