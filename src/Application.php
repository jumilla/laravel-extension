<?php namespace Jumilla\LaravelExtension;

use Illuminate\Console\AppNamespaceDetectorTrait;
use Jumilla\LaravelExtension\Addons\Addon;

class Application {

	use AppNamespaceDetectorTrait;

	public static function getNamespace()
	{
		return preg_replace('/\\\\+$/', '', (new Application)->getAppNamespace());
	}

	/**
	 * @return array
	 */
	public static function getAddonConsoleCommands()
	{
		return [];
	}

	/**
	 * @return array
	 */
	public static function getAddonHttpMiddlewares()
	{
		return [];
	}

}
