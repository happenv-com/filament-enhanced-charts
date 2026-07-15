<?php

namespace Happenv\FilamentEnhancedCharts\Option\Concerns;

/**
 * The `roam` interaction shared by pannable/zoomable series (graph, map,
 * tree, treemap): `true` enables drag-pan + wheel-zoom, `'move'` / `'scale'`
 * restrict to one of them.
 */
trait HasRoam
{
    private bool | string | null $roam = null;

    /** @param bool|string $roam `true`, `false`, `'move'` (pan only) or `'scale'` (zoom only). */
    public function roam(bool | string $roam = true): static
    {
        $this->roam = $roam;

        return $this;
    }

    /**
     * The set roam key, omitting it when unset.
     *
     * @return array<string, bool|string>
     */
    protected function roamArray(): array
    {
        return $this->roam !== null ? ['roam' => $this->roam] : [];
    }
}
