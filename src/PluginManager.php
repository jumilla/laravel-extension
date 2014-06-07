<?php namespace Jumilla\LaravelExtension;

use Illuminate\Filesystem\Filesystem;

// Plugin Manager
class PluginManager {

	public static function path()
	{
		return base_path().'/'.\Config::get('plugin.path', 'plugins');
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
