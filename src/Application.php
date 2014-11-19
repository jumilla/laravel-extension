<?php namespace LaravelPlus\Extension;

use Illuminate\Console\AppNamespaceDetectorTrait;
use LaravelPlus\Extension\Addons\AddonDirectory;

class Application {

	use AppNamespaceDetectorTrait;

	public static function getNamespace()
	{
		return preg_replace('/\\\\+$/', '', (new Application)->getAppNamespace());
	}

	/**
	 * @return array
	 */
	private static $addons = null;

	/**
	 * @return array
	 */
	public static function getAddons()
	{
		if (static::$addons === null) {
			static::$addons = AddonDirectory::addons();
		}
		return static::$addons;
	}

	/**
	 * @return array
	 */
	public static function getAddonConsoleCommands()
	{
		$commands = [];

		foreach (static::getAddons() as $addon) {
			$commands = array_merge($commands, $addon->config('console.commands', []));
		}

		return $commands;
	}

	/**
	 * @return array
	 */
	public static function getAddonHttpMiddlewares()
	{
		$middlewares = [];

		foreach (static::getAddons() as $addon) {
			$middlewares = array_merge($middlewares, $addon->config('http.middlewares', []));
		}

		return $middlewares;
	}

}
