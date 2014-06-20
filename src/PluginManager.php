<?php namespace Jumilla\LaravelExtension;

use Illuminate\Filesystem\Filesystem;

// Plugin Manager
class PluginManager {

	public static function path()
	{
		return base_path().'/'.\Config::get('plugin.path', 'plugins');
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

	public static function plugins()
	{
		$files = new Filesystem;

		$pluginsDirectory = self::path();

		// make plugins/
		if (!$files->exists($pluginsDirectory))
			$files->makeDirectory($pluginsDirectory);

		$plugins = [];
		foreach ($files->directories($pluginsDirectory) as $dir) {
			$plugins[] = new Plugin($dir);
		}
		return $plugins;
	}

}
