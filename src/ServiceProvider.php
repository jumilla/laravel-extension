<?php namespace LaravelPlus\Extension;

use Symfony\Component\Finder\Finder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Config;
use LaravelPlus\Extension\Addons\AddonManager;
use LaravelPlus\Extension\Addons\AddonClassLoader;

class ServiceProvider extends \Illuminate\Support\ServiceProvider {

	private static $commands = [
		['name' => 'command+.package.setup', 'class' => 'LaravelPlus\Extension\Commands\AddonSetupCommand'],
		['name' => 'command+.package.make', 'class' => 'LaravelPlus\Extension\Commands\AddonMakeCommand'],
		['name' => 'command+.package.check', 'class' => 'LaravelPlus\Extension\Commands\AddonCheckCommand'],
// migrate
//		['name' => 'commands.addon.migrate.generate', 'class' => 'LaravelPlus\Extension\Commands\MigrateGenerateCommand'],
// publish
		['name' => 'command+.app.container', 'class' => 'LaravelPlus\Extension\Commands\AppContainerCommand'],
// hash
		['name' => 'command+.hash.make', 'class' => 'LaravelPlus\Extension\Commands\HashMakeCommand'],
		['name' => 'command+.hash.check', 'class' => 'LaravelPlus\Extension\Commands\HashCheckCommand'],
	];

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	private $addons;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->addons = AddonManager::addons();

		$this->app['specs'] = $this->app->share(function($app) {
			$loader = new Config\FileLoader(new Filesystem, $app['path'].'/specs');
			return new Config\Repository($loader, $app['env']);
		});

		// MEMO 現在はクラスファイルの解決を動的に行うモードのみ実装している。
//		$this->loadAutoloadFiles(AddonManager::path());

		AddonClassLoader::register($this->addons);
		AliasResolver::register($this->addons, $this->app['config']->get('app.aliases'));
	}

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
//		$this->package('jumilla/laravel', 'laravel-extension', __DIR__);

		// Add package commands
		$this->setupCommands(static::$commands);

		// setup all addons
		$this->bootAddons();
	}

	/**
	 * setup package's commands.
	 *
	 * @param  $command array
	 * @return void
	 */
	protected function setupCommands($commands)
	{
		$names = [];

		foreach ($commands as $command) {
			$this->app[$command['name']] = $this->app->share(function($app) use($command) {
				return new $command['class']($app);
			});

			$names[] = $command['name'];
		}

		// Now register all the commands
		$this->commands($names);
	}

	/**
	 * setup & boot addons.
	 *
	 * @return void
	 */
	protected function bootAddons()
	{
		foreach ($this->addons as $addon) {
			$this->bootAddon($addon);
		}
	}

	/**
	 * setup & boot addon.
	 *
	 * @param  $addon \LaravelPlus\Extension\Addon
	 * @return void
	 */
	protected function bootAddon($addon)
	{
		$packageName = 'addons/'.$addon->name;

		// regist package
		$this->package($packageName, $addon->name, $addon->path);
		if (is_dir($addon->path.'/specs'))
			$this->app['specs']->package($packageName, $addon->path.'/specs', $addon->name);

		// regist service providers
		$providers = $addon->config('providers', []);
		foreach ($providers as $provider) {
			if (!starts_with($provider, '\\'))
				$provider = sprintf('%s\%s', $addon->config('namespace'), $provider);

			$this->app->register($provider);
		}

		// load *.php on addon's root directory
		$this->loadFiles($addon);
	}

	/**
	 * load addon initial script files.
	 *
	 * @param  $addon \LaravelPlus\Extension\Addon
	 * @return void
	 */
	protected function loadFiles($addon)
	{
		$files = $this->app['files'];
		foreach ($files->files($addon->path) as $file) {
			if (ends_with($file, '.php')) {
				require $file;
			}
		}
	}

	/**
	 * load 'autoload.php' files.
	 *
	 * @param  $path string
	 * @return void
	 */
	protected function loadAutoloadFiles($path)
	{
		// We will use the finder to locate all "autoload.php" files in the workbench
		// directory, then we will include them each so that they are able to load
		// the appropriate classes and file used by the given workbench package.
		$files = $this->app['files'];

		$autoloads = Finder::create()->in($path)->files()->name('autoload.php')->depth('<= 3')->followLinks();

		foreach ($autoloads as $file)
		{
			$files->requireOnce($file->getRealPath());
		}
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array_pluck(static::$commands, 'name');
	}

}
