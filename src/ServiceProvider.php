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
		$this->app['specs'] = $this->app->share(function($app) {
			$loader = new Config\FileLoader(new Filesystem, $app['path'].'/specs');
			return new Config\Repository($loader, $app['env']);
		});

		// MEMO 現在はクラスファイルの解決を動的に行うモードのみ実装している。
//		$this->loadAutoloadFiles(AddonDirectory::path());

		AddonClassLoader::register(Application::getAddons());
		AliasResolver::register(Application::getAddons(), $this->app['config']->get('app.aliases'));
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
		foreach (Application::getAddons() as $addon) {
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
		// register package
		$this->registerPackage('addons/'.$addon->name, $addon->name, $addon);

		// boot addon
		$addon->boot($this->app);
	}

	/**
	 * Register the package's component namespaces.
	 *
	 * @param  string  $package
	 * @param  string  $namespace
	 * @param  string  $path
	 * @return void
	 */
	function registerPackage($package, $namespace, $addon)
	{
		$namespace = $this->getPackageNamespace($package, $namespace);

		$config = $addon->path.'/config';
		if (is_dir($config)) {
			$this->app['config']->package($package, $config, $namespace);
		}

		$lang = $addon->path.'/'.$addon->config('paths.lang', 'lang');
		if (is_dir($lang)) {
			$this->app['translator']->addNamespace($namespace, $lang);
		}

		$view = $addon->path.'/'.$addon->config('paths.views', 'views');
		if (is_dir($view)) {
			$this->app['view']->addNamespace($namespace, $view);
		}

		$spec = $addon->path.'/specs';
		if (is_dir($spec)) {
			$this->app['specs']->package($package, $spec, $addon->name);
		}
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
