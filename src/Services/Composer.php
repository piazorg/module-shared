<?php

namespace PIA\ModuleShared\Services;

use Symfony\Component\Process\Process;
use Symfony\Component\Console\Output\OutputInterface;

class Composer
{
    public function __construct(private ?OutputInterface $output = null)  {}

    public function hasComposerPackage(string $package): bool
    {
        $packages = json_decode(file_get_contents(base_path('composer.json')), true);

        return array_key_exists($package, $packages['require'] ?? [])
            || array_key_exists($package, $packages['require-dev'] ?? []);
    }

    public function requireComposerPackage(string|array $packages, bool $asDev = false): bool
    {
        $command = array_merge(
            ['require'],
            (array) $packages,
            $asDev ? ['--dev'] : [],
        );

        return $this->runComposerCommand($command);
    }

    public function removeComposerPackage(string|array $packages, bool $asDev = false): bool
    {
        $command = array_merge(
            ['remove'],
            (array) $packages,
            $asDev ? ['--dev'] : [],
        );

        return $this->runComposerCommand($command);
    }

    private function runComposerCommand(array $arguments): bool
    {
        return (new Process(array_merge(['composer'], $arguments), base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output?->write($output);
            }) === 0;
    }
}