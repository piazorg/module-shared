<?php

namespace PIA\ModuleShared\Concerns;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use PIA\ModuleShared\Services\ModuleRemover;

trait ModuleInstaller
{
    protected function installThemedModule(): void
    {
        $this_module = explode('\\', get_class($this))[1];

        $this->copyThemeDirectory(module_path($this_module, 'theme'));

        if ($this_module !== 'Demo') {
            $this->removeDemo();
        }

        Artisan::call('cache:clear', [], $this->output);
        Artisan::call('optimize', [], $this->output);
    }

    protected function copyThemeDirectory($source): void
    {
        File::copyDirectory($source, resource_path('theme'));
    }

    protected function removeDemo(): void
    {
        $moduleRemover = app()->makeWith(ModuleRemover::class, ['output' => $this->output]);

        $moduleRemover->remove('demo');
    }
}
