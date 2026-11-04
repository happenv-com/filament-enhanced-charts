@props([
    'chartId',
    'chartOptions',
    'chartRenderer',
    'contentHeight',
    'pollingInterval',
    'loadingIndicator',
    'deferLoading',
    'readyToLoad',
    'maps' => [],
    'locale' => null,
])

<div
    {!! $deferLoading ? ' wire:init="loadWidget" ' : '' !!} class="flex items-center justify-center filament-enhanced-charts-chart">
    @if ($readyToLoad)
        {{-- `wire:ignore`: the options live in this element's `x-data`, so a morph that
             rewrote them would make Alpine tear the chart down and the restored
             `x-ignore` would keep it from coming back. New options reach the chart
             through the `updateOptions` event instead. --}}
        <div wire:ignore x-ignore x-load
             x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('filament-enhanced-charts', 'happenv/filament-enhanced-charts') }}"
             x-data="echarts({
                options: @js($chartOptions),
                chartId: '#{{ $chartId }}',
                renderer: '{{ $chartRenderer }}',
                maps: @js($maps),
                locale: @js($locale),
            })">
        </div>
        <div wire:ignore class="w-full filament-enhanced-charts-chart-container">
            <div class="filament-enhanced-charts-chart-object" x-ref="{{ $chartId }}" id="{{ $chartId }}"
                 style="position: relative; overflow:hidden;{{ 'height: '.$contentHeight.'px;' }}">
            </div>
            {{-- Initialised with the page, unlike the chart (which loads its script
                 asynchronously): an `updateOptions` that arrives before the chart
                 exists is kept on the chart element, and `initChart()` paints it
                 instead of the older options in `x-data`. --}}
            <div {!! $pollingInterval ? 'wire:poll.' . $pollingInterval . '="updateOptions"' : '' !!} x-data="{}"
                 x-init="$watch('dropdownOpen', value => $wire.dropdownOpen = value); $wire.$on('updateOptions', ({ options }) => { const host = document.getElementById(@js($chartId)); if (host) { host.__enhancedChartsLatestOptions = options } })">
            </div>
        </div>
    @else
        <div class="filament-enhanced-charts-chart-loading-indicator m-auto">
            @if ($loadingIndicator)
                {!! $loadingIndicator !!}
            @else
                <x-filament::loading-indicator class="h-7 w-7 text-gray-500 dark:text-gray-400" wire:loading.delay />
            @endif
        </div>
    @endif
</div>
