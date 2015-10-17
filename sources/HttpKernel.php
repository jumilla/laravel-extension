<?php

namespace LaravelPlus\Extension;

use Illuminate\Foundation\Http\Kernel;
use LaravelPlus\Extension\Addons\Environment as AddonEnvironment;

abstract class HttpKernel extends Kernel
{
    /**
     * Bootstrap the application for HTTP requests.
     */
    public function bootstrap()
    {
        parent::bootstrap();

        $this->middleware = array_merge($this->middleware, app(AddonEnvironment::class)->getAddonHttpMiddlewares());

        foreach (app(AddonEnvironment::class)->getAddonRouteMiddlewares() as $key => $middleware) {
            $this->router->middleware($key, $middleware);
        }
    }
}
