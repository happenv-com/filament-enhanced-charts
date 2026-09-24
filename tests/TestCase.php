<?php

namespace Happenv\FilamentEnhancedCharts\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use ErrorException;
use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Schemas\SchemasServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Happenv\FilamentEnhancedCharts\FilamentEnhancedChartsServiceProvider;
use Happenv\FilamentEnhancedCharts\Tests\Fixtures\Providers\AdminPanelProvider;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\LivewireServiceProvider;
use Livewire\Mechanisms\DataStore;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    use LazilyRefreshDatabase;
    use WithWorkbench;

    protected function setUp(): void
    {
        parent::setUp();

        // Under Testbench, Livewire's per-request DataStore doesn't survive as a
        // shared instance, so `store($component)->set(...)` and `->get(...)`
        // resolve different WeakMaps — which makes `getErrorBag()` return null
        // and every component render throw. Pin one shared DataStore per test.
        $this->app->instance(DataStore::class, new DataStore);

        // Laravel only logs deprecations. Fail the test when the package's OWN
        // code triggers one, so it is fixed before the next PHP / Laravel /
        // Filament release turns it into an error.
        $sourcePath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;

        $previousHandler = set_error_handler(function (int $level, string $message, string $file = '', int $line = 0) use (&$previousHandler, $sourcePath): bool {
            if (in_array($level, [E_DEPRECATED, E_USER_DEPRECATED], true) && str_starts_with($file, $sourcePath)) {
                throw new ErrorException($message, 0, $level, $file, $line);
            }

            return $previousHandler && (bool) $previousHandler($level, $message, $file, $line);
        });
    }

    protected function tearDown(): void
    {
        restore_error_handler();

        parent::tearDown();
    }

    /** @return array<class-string> */
    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            BladeIconsServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            SupportServiceProvider::class,
            ActionsServiceProvider::class,
            FormsServiceProvider::class,
            InfolistsServiceProvider::class,
            NotificationsServiceProvider::class,
            SchemasServiceProvider::class,
            TablesServiceProvider::class,
            WidgetsServiceProvider::class,
            FilamentServiceProvider::class,
            FilamentEnhancedChartsServiceProvider::class,
            AdminPanelProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
    }
}
