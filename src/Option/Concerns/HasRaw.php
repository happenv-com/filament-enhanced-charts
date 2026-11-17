<?php

namespace Happenv\FilamentEnhancedCharts\Option\Concerns;

trait HasRaw
{
    /** @var array<string, mixed> */
    protected array $raw = [];

    /** @param array<string, mixed> $options */
    public function raw(array $options): static
    {
        $this->raw = self::deepMerge($this->raw, $options);

        return $this;
    }

    /**
     * Deep-merge $this->raw OVER the typed output.
     *
     * @param  array<string, mixed>  $base
     * @return array<string, mixed>
     */
    protected function mergeRaw(array $base): array
    {
        return self::deepMerge($base, $this->raw);
    }

    /** A `{__js__}` or `{__number__}` marker is one value — a function — so a marker replaces another instead of merging into it. */
    private static function isMarker(array $value): bool
    {
        return isset($value['__js__']) || isset($value['__number__']);
    }

    /**
     * @param  array<string, mixed>  $base
     * @param  array<string, mixed>  $override
     * @return array<string, mixed>
     */
    private static function deepMerge(array $base, array $override): array
    {
        foreach ($override as $key => $value) {
            if (
                is_array($value) && isset($base[$key]) && is_array($base[$key])
                && ! array_is_list($value) && ! array_is_list($base[$key])
                && ! self::isMarker($value) && ! self::isMarker($base[$key])
            ) {
                $base[$key] = self::deepMerge($base[$key], $value);
            } else {
                $base[$key] = $value;
            }
        }

        return $base;
    }
}
