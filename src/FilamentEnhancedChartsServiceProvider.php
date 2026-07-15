<?php

namespace Happenv\FilamentEnhancedCharts;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Happenv\FilamentEnhancedCharts\Commands\FilamentEnhancedChartsCommand;
use Happenv\FilamentEnhancedCharts\Testing\TestsFilamentEnhancedCharts;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Blade;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentEnhancedChartsServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-enhanced-charts';

    public static string $viewNamespace = 'filament-enhanced-charts';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->hasCommands($this->getCommands());
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        parent::packageBooted();
        Blade::componentNamespace('Happenv\\FilamentEnhancedCharts\\Components', 'filament-enhanced-charts');
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/filament-enhanced-charts/{$file->getFilename()}"),
                ], 'filament-enhanced-charts-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsFilamentEnhancedCharts);
    }

    protected function getAssetPackageName(): ?string
    {
        return 'happenv/filament-enhanced-charts';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            AlpineComponent::make('filament-enhanced-charts', __DIR__ . '/../resources/dist/filament-enhanced-charts.js'),
            AlpineComponent::make('filament-enhanced-charts-column', __DIR__ . '/../resources/dist/filament-enhanced-charts-column.js'),
            // Css::make('filament-enhanced-charts-styles', __DIR__ . '/../resources/dist/filament-enhanced-charts.css'),
            // Js::make('filament-enhanced-charts-scripts', __DIR__ . '/../resources/dist/filament-enhanced-charts.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            FilamentEnhancedChartsCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }
}
