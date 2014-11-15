<?php namespace Jumilla\LaravelExtension\Addons;

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
		$config = [
			'namespace' => Application::getNamespace(),
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

	/**
	 * boot addon.
	 *
	 * @return void
	 */
	public function boot($app)
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
