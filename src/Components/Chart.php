<?php

namespace Happenv\FilamentEnhancedCharts\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Chart extends Component
{
    public function __construct(
        public $chartId,
        public $chartOptions,
        public $contentHeight,
        public $pollingInterval,
        public $loadingIndicator,
        public $deferLoading,
        public $readyToLoad
    ) {}

    /**
     * Renders a view for the chart component.
     */
    public function render(): View
    {
        return view('filament-enhanced-charts::widgets.components.chart');
    }
}
