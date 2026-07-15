<?php

namespace Happenv\FilamentEnhancedCharts\Widgets;

use Filament\Schemas\Contracts\HasSchemas;
use Filament\Widgets\Concerns\CanPoll;
use Filament\Widgets\Widget;
use Happenv\FilamentEnhancedCharts\Concerns\CanDeferLoading;
use Happenv\FilamentEnhancedCharts\Concerns\CanFilter;
use Happenv\FilamentEnhancedCharts\Concerns\HasContentHeight;
use Happenv\FilamentEnhancedCharts\Concerns\HasFooter;
use Happenv\FilamentEnhancedCharts\Concerns\HasHeader;
use Happenv\FilamentEnhancedCharts\Concerns\HasLoadingIndicator;
use Happenv\FilamentEnhancedCharts\Concerns\HasRenderer;
use Happenv\FilamentEnhancedCharts\Contracts\HasFiltersSchema;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Illuminate\Contracts\View\View;

abstract class EnhancedChartWidget extends Widget implements HasSchemas
{
    use CanDeferLoading;
    use CanFilter;
    use CanPoll;
    use HasContentHeight;
    use HasFooter;
    use HasHeader;
    use HasLoadingIndicator;
    use HasRenderer;

    protected static ?string $chartId = null;

    /**
     * Whether the mouse wheel scrolls the PAGE rather than being captured by the
     * chart (inside dataZoom wheel-zoom / roam wheel-zoom). Defaults to true so a
     * chart embedded in a scrollable page never traps the wheel; set to false on
     * a widget where wheel-zoom is the point. A per-chart `Option->scrollable()`
     * call still wins over this default.
     */
    protected static bool $scrollable = true;

    protected string $view = 'filament-enhanced-charts::widgets.echart-widget';

    /**
     * Per-request memoization of the resolved options. NOT part of the Livewire
     * snapshot — recomputed fresh on every request/poll.
     *
     * @var array<string, mixed>|null
     */
    protected ?array $resolvedOptions = null;

    /**
     * Hash of the last dispatched options, persisted across requests so the
     * poll dirty-check in updateOptions() can detect real changes.
     */
    public ?string $optionsHash = null;

    public function mount(): void
    {
        if ($this instanceof HasFiltersSchema) {
            $this->getFiltersSchema()->fill();
        }

        if (! $this->getDeferLoading()) {
            $this->readyToLoad = true;
        }

        if ($this->readyToLoad) {
            $this->optionsHash = $this->hashOptions($this->resolveOptions());
        }
    }

    /**
     * A stable hash of the resolved options, used by the poll/filter change
     * detection. Falls back to a deterministic serialize hash if `json_encode`
     * fails (e.g. invalid UTF-8) so a real change is never mistaken for "same".
     *
     * @param  array<string, mixed>  $options
     */
    protected function hashOptions(array $options): string
    {
        $json = json_encode($options);

        return md5($json === false ? serialize($options) : $json);
    }

    #[\Override]
    public function render(): View
    {
        return view($this->view, []);
    }

    protected function getChartId(): ?string
    {
        return static::$chartId ?? ('eChart_' . $this->getId());
    }

    /**
     * GeoJSON maps a `map` series / `geo` component needs, as name => URL. The
     * client fetches and `echarts.registerMap()`s each before painting. Override
     * in a map/geo widget, e.g. `['world' => asset('geo/world.json')]`.
     *
     * @return array<string, string>
     */
    public function getMaps(): array
    {
        return [];
    }

    abstract protected function getOption(): Option;

    /**
     * Final ECharts option array fed to the view (sink).
     * Override getOption() for the typed path; override this only for a raw array
     * via `getOption(): Option { return Option::make()->raw([...]); }`.
     *
     * @return array<string, mixed>
     */
    protected function getOptions(): array
    {
        return $this->getOption()
            ->applyScrollableDefault(static::$scrollable)
            ->toArray();
    }

    /**
     * Memoized per-request resolution of the widget's options. Call this instead
     * of getOptions() anywhere the options may already have been computed this
     * request (e.g. the blade view), so deferred/not-ready widgets never pay for
     * options they don't render, and ready widgets only compute once.
     *
     * @return array<string, mixed>
     */
    protected function resolveOptions(): array
    {
        return $this->resolvedOptions ??= $this->getOptions();
    }

    public function updateOptions(): void
    {
        // Prime the per-request memo so the subsequent re-render doesn't
        // compute (and query) the options a second time.
        $options = $this->resolvedOptions = $this->getOptions();
        $hash = $this->hashOptions($options);

        if ($hash !== $this->optionsHash && ! $this->dropdownOpen) {
            $this->optionsHash = $hash;

            $this
                ->dispatch('updateOptions', options: $options)
                ->self();
        }
    }
}
