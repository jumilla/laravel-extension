<?php namespace Jumilla\LaravelExtension;

class Addon {

	public $name;

	public $path;

	public $config;

	public function __construct($path)
	{
		$pathComponents = explode('/', $path);
		$this->name = $pathComponents[count($pathComponents) - 1];

		$this->path = $path;

		// TODO エラーチェック
		$this->config = require $path.'/config/addon.php';
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
