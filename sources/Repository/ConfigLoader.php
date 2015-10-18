<?php

namespace LaravelPlus\Extension\Repository;

use Illuminate\Config\Repository;
use Symfony\Component\Finder\Finder;

class ConfigLoader
{
    /**
     * Load the configuration items from all of the files.
     *
     * @param string $directoryPath
     *
     * @return Illuminate\Contracts\Config\Repository
     */
    public static function load($directoryPath)
    {
        $config = new Repository();

        (new static())->loadConfigurationFiles($directoryPath, $config);

        return $config;
    }

    /**
     * Load the configuration items from all of the files.
     *
     * @param string                                 $directoryPath
     * @param \Illuminate\Contracts\Config\Repository $config
     */
    protected function loadConfigurationFiles($directoryPath, Repository $config)
    {
        foreach ($this->getConfigurationFiles($directoryPath) as $group => $path) {
            $config->set($group, require $path);
        }
    }

    /**
     * Get all of the configuration files for the directory.
     *
     * @param string $directoryPath
     *
     * @return array
     */
    protected function getConfigurationFiles($directoryPath)
    {
        $files = [];

        if (is_dir($directoryPath)) {
            foreach (Finder::create()->files()->in($directoryPath) as $file) {
                $group = basename($file->getRealPath(), '.php');
                $files[$group] = $file->getRealPath();
            }
        }

        return $files;
    }
}
