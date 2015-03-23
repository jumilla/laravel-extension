<?php namespace LaravelPlus\Extension\Addons;

use Illuminate\Filesystem\Filesystem;

// Addon Directory
class AddonDirectory {

	/**
	 * @param  string  $name
	 * @return string
	 */
	public static function path($name = null)
	{
		if ($name) {
			return self::path() . '/' . $name;
		}
		else {
			return config('addon.path', 'addons');
		}
	}

	/**
	 * @param  string  $name
	 * @return bool
	 */
	public static function exists($name)
	{
		return is_dir(self::path($name));
	}

	/**
	 * @param  string  $relativeClassName
	 * @return string
	 */
	public static function classToPath($relativeClassName)
	{
		return str_replace('\\', '/', $relativeClassName).'.php';
	}

	/**
	 * @param  string  $relativePath
	 * @return mixed
	 */
	public static function pathToClass($relativePath)
	{
		if (strpos($relativePath, '/') !== false) {
			$relativePath = dirname($relativePath).'/'.basename($relativePath, '.php');
		}
		else {
			$relativePath = basename($relativePath, '.php');
		}

		return str_replace('/', '\\', $relativePath);
	}

	/**
	 * @param  string  $name
	 * @return Addon|null
	 */
	public static function addons()
	{
		$files = new Filesystem;

		$addonsDirectoryPath = app('path.base') .'/'. static::path();

		// make addons/
		if (!$files->exists($addonsDirectoryPath)) {
			$files->makeDirectory($addonsDirectoryPath);
		}

		$addons = [];
		foreach ($files->directories($addonsDirectoryPath) as $dir) {
			$addon = Addon::create($dir);

			$addons[$addon->name()] = $addon;
		}
		return $addons;
	}

}
