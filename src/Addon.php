<?php namespace Jumilla\LaravelExtension;

use Illuminate\Console\AppNamespaceDetectorTrait;

class Addon {

	use AppNamespaceDetectorTrait;

	public $name;

	public $path;

	public $config;

	public static function create($path)
	{
		$pathComponents = explode('/', $path);
		$name = $pathComponents[count($pathComponents) - 1];

		// TODO エラーチェック
		$config = require $path.'/config/addon.php';

		return new static($name, $path, $config);
	}

	public static function createApp()
	{
		$name = 'app';

		$path = app_path();

//		$config = require app('path.config') . '/addon.php';
		$config = [
			'namespace' => $this->getAppNamespace(),
		];

		return new static($name, $path, $config);
	}

	public function __construct($name, $path, array $config)
	{
		$this->name = $name;
		$this->path = $path;
		$this->config = $config;
	}

	public function relativePath()
	{
		return substr($this->path, strlen(base_path()) + 1);
	}

	public function config($name, $default = null)
	{
		if (isset($this->config[$name]))
			return $this->config[$name];
		else
			return $default;
	}

}
