<?php

namespace LaravelPlus\Extension\Addons\Console;

use InvalidArgumentException;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use LaravelPlus\Extension\Addons\AddonDirectory;
use LaravelPlus\Extension\Addons\Addon;

class AddonNameCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'addon:name
        {addon : The desired namespace.}
        {namespace : The desired namespace.}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[+] Set the addon PHP namespace';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Filesystem $filesystem)
    {
        $this->files = $filesystem;

        $addonName = $this->argument('addon');

        if (!AddonDirectory::exists($addonName)) {
            throw new InvalidArgumentException("Addon '$addonName' is not found.");
        }

        $this->addon = Addon::create(AddonDirectory::path($addonName));

        $this->currentNamespace = trim($this->addon->phpNamespace(), '\\');

        $this->newNamespace = str_replace('/', '\\', $this->argument('namespace'));

        $this->setAddonNamespaces();

        $this->setComposerNamespace();

        $this->setClassNamespace();

        $this->setConfigNamespaces();

        $this->info('Addon namespace set!');
    }

    /**
     * Set the namespace in addon.php, adon.json file.
     *
     * @return void
     */
    protected function setAddonNamespaces()
    {
        $this->setAddonConfigNamespaces();
        $this->setAddonJsonNamespaces();
    }

    /**
     * Set the namespace in addon.php file.
     *
     * @return void
     */
    protected function setAddonConfigNamespaces()
    {
        if (file_exists($this->addon->path('addon.php'))) {
            $search = [
                "'namespace' => '{$this->currentNamespace}'",
                "'{$this->currentNamespace}\\",
                "\"{$this->currentNamespace}\\",
                "\\{$this->currentNamespace}\\",
            ];

            $replace = [
                "'namespace' => '{$this->newNamespace}'",
                "'{$this->newNamespace}\\",
                "\"{$this->newNamespace}\\",
                "\\{$this->newNamespace}\\",
            ];

            $this->replaceIn($this->addon->path('addon.php'), $search, $replace);
        }
    }

    /**
     * Set the namespace in addon.json file.
     *
     * @return void
     */
    protected function setAddonJsonNamespaces()
    {
        if (file_exists($this->addon->path('addon.json'))) {
            $currentNamespace = str_replace('\\', '\\\\', $this->currentNamespace);
            $newNamespace = str_replace('\\', '\\\\', $this->newNamespace);

            $search = [
                "\"namespace\": \"{$currentNamespace}\"",
                "\"{$currentNamespace}\\\\",
                "\\\\{$currentNamespace}\\\\",
            ];

            $replace = [
                "\"namespace\": \"{$newNamespace}\"",
                "\"{$newNamespace}\\\\",
                "\\\\{$newNamespace}\\\\",
            ];

            $this->replaceIn($this->addon->path('addon.json'), $search, $replace);
        }
    }

    /**
     * Set the PSR-4 namespace in the Composer file.
     *
     * @return void
     */
    protected function setComposerNamespace()
    {
        if (file_exists($this->addon->path('composer.php'))) {
            $this->replaceIn(
                $this->addon->path('composer.json'), $this->currentRoot.'\\\\', str_replace('\\', '\\\\', $this->newNamespace).'\\\\'
            );
        }
    }

    /**
     * Set the namespace on the files in the class directory.
     *
     * @return void
     */
    protected function setClassNamespace()
    {
        $files = Finder::create();

        foreach ($this->addon->config('addon.directories') as $path) {
            $files->in($this->addon->path($path));
        }

        $files->name('*.php');

        $search = [
            'namespace '.$this->currentNamespace.';',
            $this->currentNamespace.'\\',
        ];

        $replace = [
            'namespace '.$this->newNamespace.';',
            $this->newNamespace.'\\',
        ];

        foreach ($files as $file) {
            $this->replaceIn($file, $search, $replace);
        }
    }

    /**
     * Set the namespace in the appropriate configuration files.
     *
     * @return void
     */
    protected function setConfigNamespaces()
    {
        $files = Finder::create()
            ->in($this->addon->path($this->addon->config('paths.config', 'config')))
            ->name('*.php');

        foreach ($files as $file) {
            $this->replaceConfigNamespaces($files->getRealPath());
        }
    }

    /**
     * Replace the namespace in PHP configuration file.
     *
     * @param  string  $path
     * @return void
     */
    protected function replaceConfigNamespaces($path)
    {
        $search = [
            "'{$this->currentNamespace}\\",
            "\"{$this->currentNamespace}\\",
            "\\{$this->currentNamespace}\\",
        ];

        $replace = [
            "'{$this->newNamespace}\\",
            "\"{$this->newNamespace}\\",
            "\\{$this->newNamespace}\\",
        ];

        $this->replaceIn($path, $search, $replace);
    }

    /**
     * Replace the given string in the given file.
     *
     * @param  string  $path
     * @param  string|array  $search
     * @param  string|array  $replace
     * @return void
     */
    protected function replaceIn($path, $search, $replace)
    {
        if ($this->output->isVerbose()) {
            $this->line("{$path} ...");
        }

        $this->files->put($path, str_replace($search, $replace, $this->files->get($path)));
    }
}
