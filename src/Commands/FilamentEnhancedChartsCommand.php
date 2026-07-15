<?php

namespace Happenv\FilamentEnhancedCharts\Commands;

use Filament\Facades\Filament;
use Filament\Panel;
use Filament\Resources\Resource;
use Filament\Support\Commands\Concerns\CanManipulateFiles;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class FilamentEnhancedChartsCommand extends Command
{
    use CanManipulateFiles;

    /**
     * Signature
     *
     * @var string
     */
    public $signature = 'make:filament-enhanced-charts {name?}';

    /**
     * Description
     *
     * @var string
     */
    public $description = 'Creates a Filament Enhanced Charts Widget class.';

    /**
     * Chart options
     */
    private array $chartOptions;

    /**
     * Create a new command instance.
     */
    public function __construct(/**
     * Filesystem instance
     */
        protected Filesystem $files
    ) {
        parent::__construct();
        $this->chartOptions = config('filament-enhanced-charts.chart_options');
    }

    public function handle(): int
    {
        // widget
        $widget = (string) str($this->argument('name') ?? text(
            label: 'What is the chart name?',
            placeholder: 'BlogPostsChart',
            required: true,
        ))
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->replace('/', '\\');

        str($widget)->afterLast('\\');

        if (str($widget)->contains('\\')) {
            str($widget)->beforeLast('\\');
        }

        $resource = null;
        $resourceClass = null;

        $chartType = select(
            label: 'What type of chart do you want to create?',
            options: $this->chartOptions,
        );

        if (class_exists(Resource::class)) {
            $resourceInput = text(
                label: 'What is the resource you would like to create this in?',
                placeholder: '[Optional] BlogPostResource',
            );

            if (filled($resourceInput)) {
                $resource = (string) str($resourceInput)
                    ->studly()
                    ->trim('/')
                    ->trim('\\')
                    ->trim(' ')
                    ->replace('/', '\\');

                if (! str($resource)->endsWith('Resource')) {
                    $resource .= 'Resource';
                }

                $resourceClass = (string) str($resource)
                    ->afterLast('\\');
            }
        }

        $panel = null;

        if (class_exists(Panel::class)) {

            $panels = Filament::getPanels();
            $namespace = config('livewire.class_namespace');

            /** @var ?Panel $panel */
            $panel = $panels[select(
                label: 'Where would you like to create this?',
                options: array_unique([
                    ...array_map(
                        fn (Panel $panel): string => "The [{$panel->getId()}] panel",
                        $panels,
                    ),
                    $namespace => "[{$namespace}] alongside other Livewire components",
                ])
            )] ?? null;
        }

        $path = null;
        $namespace = null;
        $resourcePath = null;
        $resourceNamespace = null;

        if (! $panel) {
            $namespace = config('livewire.class_namespace');
            $path = app_path((string) str($namespace)->after('App\\')->replace('\\', '/'));
        } elseif ($resource === null) {
            $widgetDirectories = $panel->getWidgetDirectories();
            $widgetNamespaces = $panel->getWidgetNamespaces();

            $namespace = (count($widgetNamespaces) > 1) ?
                select(
                    label: 'Which namespace would you like to create this in?',
                    options: $widgetNamespaces,
                ) :
                (Arr::first($widgetNamespaces) ?? 'App\\Filament\\Widgets');
            $path = (count($widgetDirectories) > 1) ?
                $widgetDirectories[array_search($namespace, $widgetNamespaces)] :
                (Arr::first($widgetDirectories) ?? app_path('Filament/Widgets/'));
        } else {
            $resourceDirectories = $panel->getResourceDirectories();
            $resourceNamespaces = $panel->getResourceNamespaces();

            $resourceNamespace = (count($resourceNamespaces) > 1) ?
                select(
                    label: 'Which namespace would you like to create this in?',
                    options: $resourceNamespaces,
                ) :
                (Arr::first($resourceNamespaces) ?? 'App\\Filament\\Resources');
            $resourcePath = (count($resourceDirectories) > 1) ?
                $resourceDirectories[array_search($resourceNamespace, $resourceNamespaces)] :
                (Arr::first($resourceDirectories) ?? app_path('Filament/Resources/'));
        }

        if ($path) {
            $this->makeDirectory($path);
            $contents = $this->getSourceFile($namespace, $widget, $chartType);
            $file = $path . '/' . $widget . '.php';
            if ($this->files->exists($file)) {
                $this->error("File : {$file} already exits!");
                exit();
            }

            $fileCount = count($this->files->files(dirname($file)));

            $this->files->put($file, $contents);

            $this->info("Successfully created {$widget}!");

        } elseif ($resourcePath) {

            $this->makeDirectory($resourcePath . '/' . $resourceClass . '/Widgets');

            $contents = $this->getSourceFile($resourceNamespace . '\\' . $resourceClass . '\\Widgets', $widget, $chartType);

            $file = $resourcePath . '/' . $resourceClass . '/Widgets/' . $widget . '.php';

            if ($this->files->exists($file)) {
                $this->error("File : {$file} already exits!");
                exit();
            }

            $fileCount = count($this->files->files(dirname($file)));

            $this->files->put($file, $contents);

            $this->info("Successfully created {$resourceClass}! Make sure to register the widget in `{$resourceClass}::getWidgets()`, and then again in `getHeaderWidgets()` or `getFooterWidgets()` of any `{$resourceClass}` page.");
        }

        return self::SUCCESS;
    }

    /**
     * Return the stub file path
     */
    public function getStubPath($chartType): string
    {
        $path = Str::of(__DIR__);

        $path = match (PHP_OS_FAMILY) {
            default => $path->replace('src/Commands', 'stubs/'),
            'Windows' => $path->replace('src\Commands', 'stubs\\')
        };

        return $path->append($chartType)->append('.stub');
    }

    /**
     **
     * Map the stub variables present in stub to its value
     */
    public function getStubVariables($namespace, $widget): array
    {
        return [
            'NAMESPACE' => $namespace,
            'CLASS_NAME' => $widget,
            'CHART_ID' => Str::of($widget)->camel(),
        ];
    }

    /**
     * Get the stub path and the stub variables
     */
    public function getSourceFile($namespace, $widget, $chartType): Stringable
    {
        return $this->getStubContents($this->getStubPath($chartType), $this->getStubVariables($namespace, $widget));
    }

    /**
     * Replace the stub variables(key) with the desire value
     */
    public function getStubContents($stub, array $stubVariables = []): Stringable
    {
        $contents = file_get_contents($stub);

        foreach ($stubVariables as $search => $replace) {
            $contents = Str::of($contents)->replace('$' . $search . '$', $replace);
        }

        return $contents;
    }

    /**
     * Build the directory for the class if necessary.
     */
    protected function makeDirectory(string $path): string
    {
        if (! $this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }
}
