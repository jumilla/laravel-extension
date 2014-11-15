<?php namespace Jumilla\LaravelExtension;

use Symfony\Component\Finder\Finder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Config;
use Jumilla\LaravelExtension\Addons\Addon;
use Jumilla\LaravelExtension\Addons\AddonDirectory;
use Jumilla\LaravelExtension\Addons\AddonClassLoader;

class ServiceProvider extends \Illuminate\Support\ServiceProvider {

	/**
	 * @var array
	 */
	private static $commands = [
		['name' => 'commands.addon.setup', 'class' => 'Jumilla\LaravelExtension\Commands\AddonSetupCommand'],
		['name' => 'commands.addon.make', 'class' => 'Jumilla\LaravelExtension\Commands\AddonMakeCommand'],
		['name' => 'commands.addon.check', 'class' => 'Jumilla\LaravelExtension\Commands\AddonCheckCommand'],
// migrate
// publish
// dump-autoload
//		['name' => 'commands.migrate.generate', 'class' => 'Jumilla\LaravelExtension\Commands\MigrateGenerateCommand'],
	];

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * @var array
	 */
	private $addons;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->addons = AddonDirectory::addons();

		$this->app['specs'] = $this->app->share(function($app) {
			$loader = new Config\FileLoader(new Filesystem, $app['path'].'/specs');
			return new Config\Repository($loader, $app['env']);
		});

		// MEMO 現在はクラスファイルの解決を動的に行うモードのみ実装している。
//		$this->loadAutoloadFiles(AddonDirectory::path());

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
	function setupCommands($commands)
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
	function bootAddons()
	{
		foreach ($this->addons as $addon) {
			$this->bootAddon($addon);
		}
	}

	/**
	 * setup & boot addon.
	 *
	 * @param  $addon \Jumilla\LaravelExtension\Addon
	 * @return void
	 */
	function bootAddon(Addon $addon)
	{
		$packageName = 'addons/'.$addon->name;

		// regist package
		$this->package($packageName, $addon->name, $addon->path);
		if (is_dir($addon->path.'/specs'))
			$this->app['specs']->package($packageName, $this->path.'/specs', $addon->name);

		// boot addon
		$addon->boot($this->app);
	}

	/**
	 * load 'autoload.php' files.
	 *
	 * @param  $path string
	 * @return void
	 */
	function loadAutoloadFiles($path)
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
