<?php

namespace LaravelPlus\Extension\Addons;

use LaravelPlus\Extension\Addons\AddonDirectory;

class Environment
{
    /**
     * @return array
     */
    protected $addons = null;

    /**
     * @return array
     */
    public function getAddons()
    {
        if ($this->addons === null) {
            $this->addons = AddonDirectory::addons();
        }

        return $this->addons;
    }

    /**
     * @return LaravelPlus\Extension\Addons\Addon
     */
    public function getAddon($name)
    {
        return array_get($this->getAddons(), $name ?: '', null);
    }

    /**
     * @return array
     */
    public function getAddonConsoleCommands()
    {
        $commands = [];

        foreach ($this->getAddons() as $addon) {
            $commands = array_merge($commands, $addon->config('addon.console.commands', []));
        }

        return $commands;
    }

    /**
     * @return array
     */
    public function getAddonHttpMiddlewares()
    {
        $middlewares = [];

        foreach ($this->getAddons() as $addon) {
            $middlewares = array_merge($middlewares, $addon->config('addon.http.middlewares', []));
        }

        return $middlewares;
    }

    /**
     * @return array
     */
    public function getAddonRouteMiddlewares()
    {
        $middlewares = [];

        foreach ($this->getAddons() as $addon) {
            $middlewares = array_merge($middlewares, $addon->config('addon.http.route_middlewares', []));
        }

        return $middlewares;
    }
}