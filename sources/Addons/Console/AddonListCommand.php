<?php

namespace LaravelPlus\Extension\Addons\Console;

use LaravelPlus\Extension\Addons\AddonDirectory;

class AddonListCommand extends AbstractCommand
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'addon:list';

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
