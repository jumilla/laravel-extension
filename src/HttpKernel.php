<?php namespace Jumilla\LaravelExtension;

use Illuminate\Foundation\Http\Kernel;

abstract class HttpKernel extends Kernel {

	/**
	 * Bootstrap the application for HTTP requests.
	 *
	 * @return void
	 */
	public function bootstrap()
	{
		$this->middleware = array_merge($this->middleware, Application::getAddonHttpMiddlewares());

		parent::bootstrap();
	}

}
