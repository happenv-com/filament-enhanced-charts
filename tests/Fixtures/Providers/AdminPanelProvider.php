<?php

namespace Happenv\FilamentEnhancedCharts\Tests\Fixtures\Providers;

use Filament\Panel;
use Filament\PanelProvider;

/**
 * A minimal default panel so Filament widgets (and the plugin's own
 * `EnhancedChartWidget`) can be mounted and rendered under Livewire in the test
 * suite.
 */
class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin');
    }
}
