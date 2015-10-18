<?php

namespace LaravelPlus\Extension\Addons\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use LaravelPlus\Extension\Addons\Directory as AddonDirectory;
use UnexpectedValueException;

class AddonRemoveCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'addon:remove
        {name : Name of addon.}
        {--force : Force remove.}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[+] Remove addon.';

    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Filesystem $filesystem)
    {
        $addonName = $this->argument('name');

        // check addon
        if (!AddonDirectory::exists($addonName)) {
            throw new UnexpectedValueException(sprintf('Addon "%s" is not found.', $addonName));
        }

        // confirm
        if (!$this->option('force')) {
            if (!$this->confirm('Are you sure? [y/N]', false)) {
                $this->comment('canceled');

                return;
            }
        }

        // process
        $filesystem->deleteDirectory(AddonDirectory::path($addonName));

        $this->info(sprintf('Addon "%s" removed.', $addonName));
    }
}
