<?php

namespace LaravelPlus\Extension\Hooks;

use Illuminate\Foundation\Application as LaravelApplication;

/**
 * @author Fumio Furukawa <fumio.furukawa@gmail.com>
 */
class ApplicationHook extends LaravelApplication
{
    /**
     *	@var LaravelApplication
     */
    private $app;

    public function __construct(LaravelApplication $app)
    {
        $this->app = $app;
    }

    /**
     * An array of the types that have been resolved.
     *
     * @return array
     */
    public function getResolved()
    {
        return $this->app->resolved;
    }

    /**
     * The container's bindings.
     *
     * @return array
     */
    public function getBindings()
    {
        return $this->app->bindings;
    }

    /**
     * The container's shared instances.
     *
     * @return array
     */
    public function getInstances()
    {
        return $this->app->instances;
    }

    /**
     * The registered type aliases.
     *
     * @return array
     */
    public function getAliases()
    {
        return $this->app->aliases;
    }

    /**
     * All of the registered tags.
     *
     * @return array
     */
    public function getTags()
    {
        return $this->app->tags;
    }
}
