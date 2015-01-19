<?php namespace LaravelPlus\Extension\Addons;

use Illuminate\Config\Repository;
use LaravelPlus\Extension\Application;
use LaravelPlus\Extension\Repository\ConfigLoader;

class Addon {

	public static function create($path)
	{
		$pathComponents = explode('/', $path);

		$name = $pathComponents[count($pathComponents) - 1];

		$config = ConfigLoader::load($path.'/config');

		return new static($name, $path, $config);
	}

	public static function createApp()
	{
		$name = 'app';

		$path = app_path();

		$config = new Repository([
			'addon' => [
				'namespace' => Application::getNamespace(),
			],
		]);

		return new static($name, $path, $config);
	}

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $path;

	/**
	 * @var \Illuminate\Config\Repository
	 */
	protected $config;

	public function __construct($name, $path, Repository $config)
	{
		$this->name = $name;
		$this->path = $path;
		$this->config = $config;
	}

	/**
	 * get name
	 *
	 * @return string
	 */
	public function name()
	{
		return $this->name;
	}

	/**
	 * get fullpath
	 *
	 * @return string
	 */
	public function path($path)
	{
		if (func_num_args() == 0) {
			return $this->path;
		}
		else {
			return $this->path . '/' . $path;
		}
	}

	/**
	 * get relative path
	 *
	 * @return string
	 */
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
		return $this->config('addon.version', 4);
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
		return $this->config->get($name, $default);
	}

	/**
	 * register addon.
	 *
	 * @param  Illuminate\Foundation\Application $app
	 * @return void
	 */
	public function register($app)
	{
		$version = $this->version();
		if ($version == 4) {
			$this->registerV4($app);
		}
		else if ($version == 5) {
			$this->registerV5($app);
		}
		else {
			throw new \Exception($version . ': Illigal addon version.');
		}
	}

	/**
	 * register addon version 4.
	 *
	 * @param  Illuminate\Foundation\Application $app
	 * @return void
	 */
	private function registerV4($app)
	{
		$this->config['paths'] = [
			'assets' => 'assets',
			'lang' => 'lang',
			'migrations' => 'migrations',
			'seeds' => 'seeds',
			'specs' => 'specs',
			'views' => 'views',
		];

		// regist service providers
		$providers = $this->config('addon.providers', []);
		foreach ($providers as $provider) {
			if (!starts_with($provider, '\\'))
				$provider = sprintf('%s\%s', $this->config('addon.namespace'), $provider);

			$app->register($provider);
		}
	}

	/**
	 * register addon version 5.
	 *
	 * @param  Illuminate\Foundation\Application $app
	 * @return void
	 */
	private function registerV5($app)
	{
		// regist service providers
		$providers = $this->config('addon.providers', []);
		foreach ($providers as $provider) {
			$app->register($provider);
		}
	}

	/**
	 * boot addon.
	 *
	 * @param  Illuminate\Foundation\Application $app
	 * @return void
	 */
	public function boot($app)
	{
		$version = $this->version();
		if ($version == 4) {
			$this->bootV4($app);
		}
		else if ($version == 5) {
			$this->bootV5($app);
		}
		else {
			throw new \Exception($version . ': Illigal addon version.');
		}
	}

	/**
	 * boot addon version 4.
	 *
	 * @param  Illuminate\Foundation\Application $app
	 * @return void
	 */
	private function bootV4($app)
	{
		// load *.php on addon's root directory
		$this->loadFiles($app);
	}

	/**
	 * boot addon version 5.
	 *
	 * @param  Illuminate\Foundation\Application $app
	 * @return void
	 */
	private function bootV5($app)
	{
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
