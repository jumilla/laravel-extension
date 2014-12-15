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
	 * @return LaravelPlus\Extension\Addons\Addon|null
	 */
	public static function getAddon($name)
	{
		return array_get(static::getAddons(), $name);
	}

	/**
	 * @return array
	 */
	public static function getAddonConsoleCommands()
	{
		$commands = [];

		foreach (static::getAddons() as $addon) {
			$commands = array_merge($commands, $addon->config('addon.console.commands', []));
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
			$middlewares = array_merge($middlewares, $addon->config('addon.http.middlewares', []));
		}

		return $middlewares;
	}

	/**
	 * @return array
	 */
	public static function getAddonRouteMiddlewares()
	{
		$middlewares = [];

		foreach (static::getAddons() as $addon) {
			$middlewares = array_merge($middlewares, $addon->config('addon.http.route_middlewares', []));
		}

		return $middlewares;
	}

}
