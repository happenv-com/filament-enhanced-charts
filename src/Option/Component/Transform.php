<?php

namespace Happenv\FilamentEnhancedCharts\Option\Component;

use Happenv\FilamentEnhancedCharts\Option\Concerns\HasRaw;
use Happenv\FilamentEnhancedCharts\Option\Contracts\Node;
use Illuminate\Support\Traits\Conditionable;

final class Transform implements Node
{
    use Conditionable;
    use HasRaw;

    private ?string $type = null;

    /** @var array<string, mixed>|list<array<string, mixed>>|null */
    private ?array $config = null;

    private ?bool $print = null;

    public static function make(): self
    {
        return new self;
    }

    /**
     * The built-in `filter` transform: keep only the rows matching a relational
     * condition, e.g. `Transform::filter(['dimension' => 'Year', 'gte' => 2011])`.
     *
     * @param  array<string, mixed>  $config
     */
    public static function filter(array $config): self
    {
        return self::make()->type('filter')->config($config);
    }

    /**
     * The built-in `sort` transform: order rows by one or more dimensions, e.g.
     * `Transform::sort(['dimension' => 'value', 'order' => 'desc'])`, or a list
     * of such expressions to sort by multiple dimensions in order.
     *
     * @param  array<string, mixed>|list<array<string, mixed>>  $config
     */
    public static function sort(array $config): self
    {
        return self::make()->type('sort')->config($config);
    }

    /** The transform type: a built-in (`filter`/`sort`) or a registered third-party name. */
    public function type(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /** @param array<string, mixed>|list<array<string, mixed>> $config */
    public function config(array $config): self
    {
        $this->config = $config;

        return $this;
    }

    /** Prints the transform's result to the browser console, for debugging. */
    public function print(bool $print = true): self
    {
        $this->print = $print;

        return $this;
    }

    public function toArray(): array
    {
        $transform = [];

        if ($this->type !== null) {
            $transform['type'] = $this->type;
        }
        if ($this->config !== null) {
            $transform['config'] = $this->config;
        }
        if ($this->print !== null) {
            $transform['print'] = $this->print;
        }

        return $this->mergeRaw($transform);
    }
}
