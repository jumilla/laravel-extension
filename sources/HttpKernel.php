<?php

namespace LaravelPlus\Extension;

use Illuminate\Foundation\Http\Kernel;

abstract class HttpKernel extends Kernel
{
    /**
     * Bootstrap the application for HTTP requests.
     *
     * @return void
     */
    public function bootstrap()
    {
        parent::bootstrap();

        $this->middleware = array_merge($this->middleware, Application::getAddonHttpMiddlewares());

        foreach (Application::getAddonRouteMiddlewares() as $key => $middleware) {
            $this->router->middleware($key, $middleware);
        }
    }
}
