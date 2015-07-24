<?php

namespace LaravelPlus\Extension\Addons\Console;

use Illuminate\Console\Command;
use Symfony\Component\Finder\Finder;
use LaravelPlus\Extension\Addons\AddonDirectory;

class AddonCheckCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'addon:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[+] Check addons';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $files = $this->laravel['files'];

        // make addons/
        $addonsDirectory = AddonDirectory::path();
        if (!$files->exists($addonsDirectory)) {
            $files->makeDirectory($addonsDirectory);
        }

        $this->line('> Check Start.');
        $this->line('--------');

        $addons = AddonDirectory::addons();
        foreach ($addons as $addon) {
            $this->dump($addon);
        }

        $this->line('> Check Finished!');
    }

    protected function dump($addon)
    {
        $this->dumpProperties($addon);
        $this->dumpClasses($addon);
        $this->dumpServiceProviders($addon);

        $this->line('--------');
    }

    protected function dumpProperties($addon)
    {
        $this->info(sprintf('Addon "%s"', $addon->name()));
        $this->info(sprintf('Path: %s', $addon->relativePath()));
        $this->info(sprintf('PHP namespace: %s', $addon->config('addon.namespace')));
    }

    protected function dumpClasses($addon)
    {
        // load laravel services
        $files = $this->laravel['files'];

        // 全ディレクトリ下を探索する (PSR-4)
        foreach ($addon->config('addon.directories') as $directory) {
            $this->info(sprintf('PHP classes on "%s"', $directory));

            $classDirectoryPath = $addon->path($directory);

            if (!file_exists($classDirectoryPath)) {
                $this->line(sprintf('Warning: Class directory "%s" not found', $directory));
                continue;
            }

            // recursive find files
            $phpFilePaths = iterator_to_array((new Finder)->in($classDirectoryPath)->name('*.php')->files(), false);

            foreach ($phpFilePaths as $phpFilePath) {
                $relativePath = substr($phpFilePath, strlen($classDirectoryPath) + 1);

                $classFullName = $addon->config('addon.namespace').'\\'.AddonDirectory::pathToClass($relativePath);

                $this->line(sprintf('  "%s" => %s', $relativePath, $classFullName));
            }
        }
    }

    protected function dumpServiceProviders($addon)
    {
    }
}
