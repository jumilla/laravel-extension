<?php

namespace LaravelPlus\Extension\Addons\Console;

use Illuminate\Console\Command;
use LaravelPlus\Extension\Addons\AddonDirectory;

/**
 * Modules console commands.
 * @author Fumio Furukawa <fumio.furukawa@gmail.com>
 */
class AddonSetupCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'addon:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[+] Setup addon architecture';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // make addons/
        $addonsDirectory = AddonDirectory::path();
        if (!$this->files->exists($addonsDirectory)) {
            $this->files->makeDirectory($addonsDirectory);

            $this->info('make directory: '.$addonsDirectory);
        }

        // copy app/config/addon.php
        $addonConfigSourceFile = __DIR__.'/../../config/addon.php';
        $addonConfigFile = app('path.config').'/addon.php';
        if (!$this->files->exists($addonConfigFile)) {
            $this->files->copy($addonConfigSourceFile, $addonConfigFile);

            $this->info('make config: '.$addonConfigFile);
        }

        $this->info('Setup Succeeded.');
    }
}
