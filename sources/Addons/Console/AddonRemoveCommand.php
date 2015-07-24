<?php

namespace LaravelPlus\Extension\Addons\Console;

use Illuminate\Console\Command;
use LaravelPlus\Extension\Addons\AddonDirectory;

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // setup addon environment
        $this->call('addon:setup');

        $addonName = $this->argument('name');

        // check addon
        if (!AddonDirectory::exists($addonName)) {
            $this->error(sprintf('Addon "%s" is not found.', $addonName));

            return;
        }

        // confirm
        if (!$this->option('force')) {
            if (!$this->confirm('Are you sure? [y/N]', false)) {
                $this->comment('canceled');

                return;
            }
        }

        // process
        $this->files->deleteDirectory(AddonDirectory::path($addonName));

        $this->info(sprintf('Addon "%s" removed.', $addonName));
    }
}
