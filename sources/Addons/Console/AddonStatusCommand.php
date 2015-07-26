<?php

namespace LaravelPlus\Extension\Addons\Console;

use Illuminate\Console\Command;
use LaravelPlus\Extension\Addons\AddonDirectory;

class AddonStatusCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'addon:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[+] List up addon information';

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

        // copy app/config/addon.php
        $addonConfigSourceFile = __DIR__.'/../../../config/addon.php';
        $addonConfigFile = app('path.config').'/addon.php';
        if (!$files->exists($addonConfigFile)) {
            $files->copy($addonConfigSourceFile, $addonConfigFile);

            $this->info('make config: '.$addonConfigFile);
        }

        // show lists
        $addons = AddonDirectory::addons();
        foreach ($addons as $addon) {
            $this->dump($addon);
        }
    }

    protected function dump($addon)
    {
        $this->line($addon->name());
    }
}
