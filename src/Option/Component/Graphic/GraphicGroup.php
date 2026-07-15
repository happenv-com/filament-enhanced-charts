<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Option\Component\Graphic;

use Happenv\FilamentEnhancedCharts\Option\Support\Normalize;

final class GraphicGroup extends GraphicElement
{
    /** @var list<GraphicElement> */
    private array $children = [];

    protected function elementType(): string
    {
        return 'group';
    }

    /** @param iterable<GraphicElement> $children */
    public function children(iterable $children): static
    {
        $this->children = Normalize::list($children);

        return $this;
    }

    #[\Override]
    protected function build(): array
    {
        $element = parent::build();
        if ($this->children !== []) {
            $element['children'] = Normalize::value($this->children);
        }

        return $element;
    }
}
