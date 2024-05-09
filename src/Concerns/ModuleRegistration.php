<?php

namespace PIA\ModuleShared\Concerns;

use Illuminate\Support\Facades\Blade;

trait ModuleRegistration
{
    protected function moduleName()
    {
        return explode('\\', get_class($this))[1];
    }

    protected function moduleNameLower()
    {
        return strtolower($this->moduleName());
    }

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerCommands();
        $this->registerCommandSchedules();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName(), 'Database/migrations'));

        $this->registrationBoot();
    }

    public function registrationBoot(): void
    {
        //
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        //
    }

    /**
     * Register commands in the format of Command::class
     */
    protected function registerCommands(): void
    {
        // $this->commands([]);
    }

    /**
     * Register command Schedules.
     */
    protected function registerCommandSchedules(): void
    {
        // $this->app->booted(function () {
        //     $schedule = $this->app->make(Schedule::class);
        //     $schedule->command('inspire')->hourly();
        // });
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/'.$this->moduleNameLower());

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower());
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName(), 'lang'), $this->moduleNameLower());
            $this->loadJsonTranslationsFrom(module_path($this->moduleName(), 'lang'));
        }
    }

    /**
     * Register config.
     */
    protected function registerConfig(array $filenames = ['config'])
    {
        foreach ($filenames as $filename) {
            $this->publishes([
                module_path($this->moduleName(), "config/{$filename}.php") => config_path($this->moduleNameLower() . '.php'),
            ], $filename);
            $this->mergeConfigFrom(
                module_path($this->moduleName(), "config/{$filename}.php"), $this->moduleNameLower() . '.' . $filename
            );
        }
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/'.$this->moduleNameLower());
        $sourcePath = module_path($this->moduleName(), 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->moduleNameLower().'-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower());

        $componentNamespace = str_replace('/', '\\', config('modules.namespace').'\\'.$this->moduleName().'\\'.config('modules.paths.generator.component-class.path'));
        Blade::componentNamespace($componentNamespace, $this->moduleNameLower());
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path.'/modules/'.$this->moduleNameLower())) {
                $paths[] = $path.'/modules/'.$this->moduleNameLower();
            }
        }

        return $paths;
    }    
}
