<?php namespace LaravelPlus\Extension\Addons;

use LaravelPlus\Extension\Application;

class Addon {

	public static function create($path)
	{
		$pathComponents = explode('/', $path);
		$name = $pathComponents[count($pathComponents) - 1];

		$configFilePath = $path.'/config/addon.php';
		if (file_exists($configFilePath)) {
			$config = require $configFilePath;
		}
		else {
//			throw new \Exception(sprintf('"%s" not found.', $configFilePath));
			$config = [];
		}

		return new static($name, $path, $config);
	}

	public static function createApp()
	{
		$name = 'app';

		$path = app_path();

		$config = [
			'namespace' => Application::getNamespace(),
		];

		return new static($name, $path, $config);
	}

	/**
	 * @var string $name
	 */
	public $name;

	public $path;

	public $config;

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

	/**
	 * get version.
	 *
	 * @return integer
	 */
	public function version()
	{
		return $this->config('version', 4);
	}

	/**
	 * get config value.
	 *
	 * @param  string $name
	 * @param  mixed  $default
	 * @return integer
	 */
	public function config($name, $default = null)
	{
		return array_get($this->config, $name, $default);
	}

	/**
	 * boot addon.
	 *
	 * @return void
	 */
	public function boot($app)
	{
		$version = $this->version();
		if ($version == 4) {
			$this->boot4($app);
		}
		else if ($version == 5) {
			$this->boot5($app);
		}
		else {
			throw new \Exception($version . ': Illigal addon version.');
		}
	}

	/**
	 * boot addon version 4.
	 *
	 * @return void
	 */
	private function boot4($app)
	{
		// regist service providers
		$providers = $this->config('providers', []);
		foreach ($providers as $provider) {
			if (!starts_with($provider, '\\'))
				$provider = sprintf('%s\%s', $this->config('namespace'), $provider);

			$app->register($provider);
		}

		// load *.php on addon's root directory
		$this->loadFiles($app);
	}

	/**
	 * boot addon version 5.
	 *
	 * @return void
	 */
	private function boot5($app)
	{
		// regist service providers
		$providers = $this->config('providers', []);
		foreach ($providers as $provider) {
			// TODO: 埋め込み変数形式に変更する
			if (!starts_with($provider, '\\'))
				$provider = sprintf('%s\%s', $this->config('namespace'), $provider);

			$app->register($provider);
		}

		// load *.php on addon's root directory
		$this->loadFiles($app);
	}

	/**
	 * load addon initial script files.
	 *
	 * @return void
	 */
	private function loadFiles($app)
	{
		$files = $app['files'];
		foreach ($files->files($this->path) as $file) {
			if (ends_with($file, '.php')) {
				require $file;
			}
		}
	}

}
