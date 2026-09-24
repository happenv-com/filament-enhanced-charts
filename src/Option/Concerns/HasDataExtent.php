<?php

namespace Happenv\FilamentEnhancedCharts\Option\Concerns;

use BcMath\Number;

/**
 * Numeric axes (value/log/time) only: values the nice axis extent must include
 * on top of the data, so the axis reaches e.g. a target line with no hard min/max.
 */
trait HasDataExtent
{
    private int | float | string | Number | null $dataMin = null;

    private int | float | string | Number | null $dataMax = null;

    /** The axis extent includes this value; the data minimum still wins when it is lower. */
    public function dataMin(int | float | string | Number $dataMin): static
    {
        $this->dataMin = $dataMin;

        return $this;
    }

    /** The axis extent includes this value; the data maximum still wins when it is higher. */
    public function dataMax(int | float | string | Number $dataMax): static
    {
        $this->dataMax = $dataMax;

        return $this;
    }

    /** @return array<string, int|float|string|Number> */
    protected function dataExtentArray(): array
    {
        $extent = [];
        if ($this->dataMin !== null) {
            $extent['dataMin'] = $this->dataMin;
        }
        if ($this->dataMax !== null) {
            $extent['dataMax'] = $this->dataMax;
        }

        return $extent;
    }
}
