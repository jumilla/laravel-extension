<?php namespace Jumilla\Laravel;

use Illuminate\Filesystem\Filesystem;

// Package Manager
class PackageCollection {

	public static function path()
	{
		return \Config::get('package.path', base_path().'/packages');
	}

	public static function packages()
	{
		$files = new Filesystem;

		$packages = [];
		foreach ($files->directories(self::path()) as $dir) {
			$packages[] = new Package($dir);
		}
		return $packages;
	}

}
