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
])

<div
    {!! $deferLoading ? ' wire:init="loadWidget" ' : '' !!} class="flex items-center justify-center filament-enhanced-charts-chart">
    @if ($readyToLoad)
        <div x-ignore x-load
             x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('filament-enhanced-charts', 'happenv/filament-enhanced-charts') }}"
             x-data="echarts({
                options: @js($chartOptions),
                chartId: '#{{ $chartId }}',
                renderer: '{{ $chartRenderer }}',
                maps: @js($maps),
            })">
        </div>
        <div wire:ignore class="w-full filament-enhanced-charts-chart-container">
            <div class="filament-enhanced-charts-chart-object" x-ref="{{ $chartId }}" id="{{ $chartId }}"
                 style="position: relative; overflow:hidden;{{ 'height: '.$contentHeight.'px;' }}">
            </div>
            <div {!! $pollingInterval ? 'wire:poll.' . $pollingInterval . '="updateOptions"' : '' !!} x-data="{}"
                 x-init="$watch('dropdownOpen', value => $wire.dropdownOpen = value)">
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
