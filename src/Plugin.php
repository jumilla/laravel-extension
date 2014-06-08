<?php namespace Jumilla\LaravelExtension;

class Plugin {

	public $name;

	public $path;

	public $config;

	public function __construct($path)
	{
		$pathComponents = explode('/', $path);
		$this->name = $pathComponents[count($pathComponents) - 1];

		$this->path = $path;

		$this->config = require $path.'/config/plugin.php';
	}

	public function config($name, $default = null)
	{
		if (isset($this->config[$name]))
			return $this->config[$name];
		else
			return $default;
	}

}
