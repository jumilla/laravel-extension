<?php namespace LaravelPlus\Extension\Addons;

use Illuminate\Filesystem\Filesystem;

// Addon Manager
class AddonManager {

	/**
	 *
	 */
	public static function setup()
	{
		$files = new Filesystem;

		$addonsDirectory = self::path();

		// make addons/
		if (!$files->exists($addonsDirectory)) {
			$files->makeDirectory($addonsDirectory);
		}
	}

	/**
	 * @param null $name
	 * @return string
	 */
	public static function path($name = null)
	{
		if ($name) {
			return self::path() . '/' . $name;
		}
		else {
			return base_path().'/'.\Config::get('addon.path', 'addons');
		}
	}

	/**
	 * @param $name
	 * @return bool
	 */
	public static function exists($name)
	{
		return is_dir(self::path($name));
	}

	/**
	 * @param $relativeClassName
	 * @return string
	 */
	public static function classToPath($relativeClassName)
	{
		return str_replace('\\', '/', $relativeClassName).'.php';
	}

	/**
	 * @param $relativePath
	 * @return mixed
	 */
	public static function pathToClass($relativePath)
	{
		if (strpos($relativePath, '/') !== false) {
			$relativePath = dirname($relativePath) . '/' . basename($relativePath, '.php');
		}
		else {
			$relativePath = basename($relativePath, '.php');
		}

		return str_replace('/', '\\', $relativePath);
	}

	/**
	 * @param $name
	 * @return Addon|null
	 */
	public static function addon($name)
	{
		if (! self::exists($name)) {
			return null;
		}

		return new Addon(self::path($name));
	}

	/**
	 * @return array
	 */
	public static function addons()
	{
		$files = new Filesystem;

		$addonsDirectory = self::path();

		// make addons/
		if (!$files->exists($addonsDirectory)) {
			$files->makeDirectory($addonsDirectory);
		}

		$addons = [];
		foreach ($files->directories($addonsDirectory) as $dir) {
			$addons[] = new Addon($dir);
		}
		return $addons;
	}

}
