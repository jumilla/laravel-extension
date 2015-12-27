<?php

namespace LaravelPlus\Extension;

use Illuminate\Foundation\Http\Kernel;
use Jumilla\Addomnipot\Laravel\Environment as AddonEnvironment;

abstract class HttpKernel extends Kernel
{
    /**
     * Bootstrap the application for HTTP requests.
     */
    public function bootstrap()
    {
        parent::bootstrap();

        $this->middleware = array_merge($this->middleware, app(AddonEnvironment::class)->addonHttpMiddlewares());

        foreach (app(AddonEnvironment::class)->addonRouteMiddlewares() as $key => $middleware) {
            $this->router->middleware($key, $middleware);
        }
    }
}
