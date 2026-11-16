<?php

declare(strict_types=1);

namespace Happenv\FilamentEnhancedCharts\Presets;

use Happenv\FilamentEnhancedCharts\Data\ChartDataset;
use Happenv\FilamentEnhancedCharts\Data\DatasetShape;
use Happenv\FilamentEnhancedCharts\Exceptions\ChartPresetException;
use Happenv\FilamentEnhancedCharts\Option\Option;

/**
 * The registry of chart presets: the built-ins, plus whatever an app or plugin
 * registers. A consumer holding a `ChartDataset` asks `accepting()` for the
 * presets that can draw it and builds the `Option` — it never needs to know a
 * chart type, so a preset registered later is available with no consumer change.
 *
 * Bound as a singleton; it holds only registered (stateless) presets, so it is
 * safe under Octane as long as presets are registered while booting.
 */
final class ChartPresets
{
    private const string NAME_PATTERN = '/^[a-z0-9]+(?:-[a-z0-9]+)*$/';

    /** @var array<string, ChartPreset> */
    private array $presets = [];

    public function __construct()
    {
        foreach (self::builtIn() as $preset) {
            $this->register($preset);
        }
    }

    /**
     * Register a preset; one registered later under the same name replaces the
     * earlier one (and keeps its place in the list).
     */
    public function register(ChartPreset $preset): self
    {
        $name = $preset->name();

        if (preg_match(self::NAME_PATTERN, $name) !== 1) {
            throw ChartPresetException::invalidName($name);
        }

        $this->presets[$name] = $preset;

        return $this;
    }

    /** @return list<ChartPreset> */
    public function all(): array
    {
        return array_values($this->presets);
    }

    public function has(string $name): bool
    {
        return isset($this->presets[$name]);
    }

    public function find(string $name): ?ChartPreset
    {
        return $this->presets[$name] ?? null;
    }

    /** @throws ChartPresetException when no preset has that name */
    public function get(string $name): ChartPreset
    {
        return $this->find($name) ?? throw ChartPresetException::unknown($name);
    }

    /**
     * The presets that can draw a dataset of this shape, in registration order.
     *
     * @return list<ChartPreset>
     */
    public function accepting(DatasetShape $shape): array
    {
        return array_values(array_filter(
            $this->presets,
            static fn (ChartPreset $preset): bool => $preset->accepts($shape),
        ));
    }

    /**
     * The presets as plain data — what a person or an LLM picks from: all of
     * them, or only those accepting the given shape.
     *
     * @return list<array{name: string, description: string, requirement: string}>
     */
    public function catalog(?DatasetShape $shape = null): array
    {
        $presets = $shape instanceof DatasetShape ? $this->accepting($shape) : $this->all();

        return array_map(static fn (ChartPreset $preset): array => [
            'name' => $preset->name(),
            'description' => $preset->description(),
            'requirement' => $preset->requirement(),
        ], $presets);
    }

    /**
     * Build the option for a dataset with the named preset.
     *
     * @throws ChartPresetException when the preset is unknown or does not accept the dataset's shape
     */
    public function option(string $name, ChartDataset $dataset): Option
    {
        return $this->get($name)->option($dataset);
    }

    /** @return list<ChartPreset> */
    public static function builtIn(): array
    {
        return [
            new Builtin\PiePreset,
            new Builtin\DonutPreset,
            new Builtin\RosePreset,
            new Builtin\FunnelPreset,
            new Builtin\BarPreset,
            new Builtin\HorizontalBarPreset,
            new Builtin\LinePreset,
            new Builtin\SmoothLinePreset,
            new Builtin\AreaPreset,
            new Builtin\GroupedBarPreset,
            new Builtin\StackedBarPreset,
            new Builtin\StackedHorizontalBarPreset,
            new Builtin\MultiLinePreset,
            new Builtin\StackedAreaPreset,
            new Builtin\HeatmapPreset,
            new Builtin\SankeyPreset,
            new Builtin\TreemapPreset,
            new Builtin\SunburstPreset,
            new Builtin\RadarPreset,
            new Builtin\PolarBarPreset,
            new Builtin\GaugePreset,
            new Builtin\ThemeRiverPreset,
            new Builtin\CalendarHeatmapPreset,
            new Builtin\ScatterPreset,
        ];
    }
}
