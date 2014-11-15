<?php namespace Jumilla\LaravelExtension;

class Addon {

	/**
	 * @var string $name
	 */
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

		$instance = new static($name, $path, []);
		$instance->config['namespace'] = Application::getNamespace();
		return $instance;
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
