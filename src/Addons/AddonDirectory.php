<?php namespace LaravelPlus\Extension\Addons;

use Illuminate\Filesystem\Filesystem;

// Addon Directory
class AddonDirectory {

	public static function path()
	{
		$configFilePath = app('path.config') . '/addon.php';

		if (file_exists($configFilePath)) {
			$config = require $configFilePath;
		}
		else {
			$config = [];
		}

		return array_get($config, 'path', 'addons');
	}

	public static function classToPath($relativeClassName)
	{
		return str_replace('\\', '/', $relativeClassName).'.php';
	}

	public static function pathToClass($relativePath)
	{
		if (strpos($relativePath, '/') !== false)
			$relativePath = dirname($relativePath).'/'.basename($relativePath, '.php');
		else
			$relativePath = basename($relativePath, '.php');

		return str_replace('/', '\\', $relativePath);
	}

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
