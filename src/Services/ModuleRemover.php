<?php

namespace PIA\ModuleShared\Services;

use Symfony\Component\Console\Output\OutputInterface;
use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Arr;

use function Illuminate\Filesystem\join_paths;

class ModuleRemover
{
    private Composer $composer;

    public function __construct(private ?OutputInterface $output = null)
    {
        $this->composer = app()->makeWith(Composer::class, ['output' => $output]);
    }

    public function remove(string $module)
    {
        $this->removeModule($module);

        $moduleName = strtolower($module);
        $composerPackageName = "pia/{$moduleName}-module";

        if ($this->composer->hasComposerPackage($composerPackageName)) {
            $this->composer->removeComposerPackage($composerPackageName);
        }
    }

    protected function removeModule(string $moduleName): void
    {
        $module = Module::find($moduleName);

        if (! $module) {
            return;
        }

        if ($this->isRealDir($module->getPath())) {
            $module->delete();

            return;
        }

        $modules = rescue(fn () => json_decode(file_get_contents(base_path('modules_statuses.json')), true));

        if (! $modules) {
            return;
        }
        
        file_put_contents(base_path('modules_statuses.json'), json_encode(Arr::except($modules, [$module->getName()]), JSON_PRETTY_PRINT));
    }

    private function isRealDir(string $path): bool
    {
        if (!is_dir($path)) {
            return false;
        }

        $realpath = realpath($path);

        if (!$realpath) {
            return false;
        }

        $path_sanitized = join_paths(...array_values(Arr::only(pathinfo($path), ['dirname', 'basename'])));
        $realpath_sanitized = join_paths(...array_values(Arr::only(pathinfo($realpath), ['dirname', 'basename'])));

        return $path_sanitized === $realpath_sanitized;
    }
}