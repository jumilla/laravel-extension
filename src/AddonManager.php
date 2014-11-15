<?php namespace Jumilla\LaravelExtension;

use Illuminate\Filesystem\Filesystem;

// Addon Manager
class AddonManager {

	public static function path()
	{
		return base_path().'/'.\Config::get('addon.path', 'addons');
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

		$addonsDirectory = self::path();

		// make addons/
		if (!$files->exists($addonsDirectory))
			$files->makeDirectory($addonsDirectory);

		$addons = [];
		foreach ($files->directories($addonsDirectory) as $dir) {
			$addons[] = Addon::create($dir);
		}
		return $addons;
	}

}
