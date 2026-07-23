<?php

declare(strict_types=1);

use Happenv\FilamentEnhancedCharts\Option\Axis\CategoryAxis;
use Happenv\FilamentEnhancedCharts\Option\Option;
use Happenv\FilamentEnhancedCharts\Option\Series\LineSeries;
use Happenv\FilamentEnhancedCharts\Widgets\EnhancedChartWidget;

covers(EnhancedChartWidget::class);

function makeEnhancedChartTestWidget(): EnhancedChartWidget
{
    return new class extends EnhancedChartWidget
    {
        protected function getOption(): Option
        {
            return Option::make()
                ->xAxis(CategoryAxis::make()->data(['a', 'b']))
                ->series(LineSeries::make()->data([1, 2]));
        }

        /** @return array<string, mixed> */
        public function exposedOptions(): array
        {
            return $this->getOptions();
        }
    };
}

it('defaults dropdownOpen to false, matching the Alpine-side initial state', function () {
    // Regression: a `true` default permanently gated updateOptions() — the
    // Alpine $watch sync only fires on change, so a widget whose dropdown is
    // never touched would keep the server-side property at its default forever.
    expect(makeEnhancedChartTestWidget()->dropdownOpen)->toBeFalse();
});

it('applies the widget scrollable default to the resolved options', function () {
    $options = makeEnhancedChartTestWidget()->exposedOptions();

    // The default $scrollable = true leaves plain options untouched (no
    // dataZoom/roam present) — but the pipeline must run without error and
    // produce the option tree.
    expect($options)->toHaveKeys(['xAxis', 'yAxis', 'series']);
});

it('hashes options deterministically and falls back to serialize on encode failure', function () {
    $widget = makeEnhancedChartTestWidget();
    $hash = (new ReflectionMethod($widget, 'hashOptions'))->getClosure($widget);

    // Deterministic and distinguishing.
    expect($hash(['a' => 1]))->toBe($hash(['a' => 1]))
        ->and($hash(['a' => 1]))->not->toBe($hash(['a' => 2]))
        ->and($hash(['a' => 1]))->toHaveLength(32);

    // An invalid-UTF-8 payload makes json_encode return false; the serialize
    // fallback still yields a stable, distinguishing 32-char hash (rather than
    // collapsing every failing payload to md5('')).
    expect($hash(["\xB1\x31"]))->toHaveLength(32)
        ->and($hash(["\xB1\x31"]))->not->toBe($hash(["\xB1\x32"]));
});
