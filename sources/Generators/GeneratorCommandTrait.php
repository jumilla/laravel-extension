<?php

namespace LaravelPlus\Extension\Generators;

use Jumilla\Addomnipot\Laravel\Environment as AddonEnvironment;
use Jumilla\Addomnipot\Laravel\Addon;
use UnexpectedValueException;

trait GeneratorCommandTrait
{
    /**
     * addon.
     *
     * @var \LaravelPlus\Extension\Addons\Addon
     */
    protected $addon;

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle()
    {
        if (!$this->processArguments()) {
            return false;
        }

        $this->addon = $this->getAddon();

        return parent::handle();
    }

    /**
     * Process command line arguments
     *
     * @return bool
     */
    protected function processArguments()
    {
        return true;
    }

    /**
     * Get addon.
     *
     * @return string
     */
    protected function getAddon()
    {
        if ($addon = $this->option('addon')) {
            $env = app(AddonEnvironment::class);

            if (!$env->exists($addon)) {
                throw new UnexpectedValueException("Addon '$addon' is not found.");
            }

            return Addon::create($env->path($addon));
        } else {
            return;
        }
    }

    /**
     * Get the application namespace.
     *
     * @return $string
     */
    protected function getAppNamespace()
    {
        return trim($this->laravel->getNamespace(), '\\');
    }

    /**
    * Get the addon namespace.
     *
     * @return $string
     */
    protected function getAddonNamespace()
    {
        return $this->addon->phpNamespace();
    }

    /**
     * Get the default namespace for the class.
     *
     * @return $string
     */
    protected function getRootNamespace()
    {
        return $this->addon ? $this->getAddonNamespace() : $this->getAppNamespace();
    }

    /**
     * Get the directory path for root namespace.
     *
     * @return string
     */
    protected function getRootDirectory()
    {
        return $this->addon ? $this->addon->path('classes') : parent::getRootDirectory();
    }
}
