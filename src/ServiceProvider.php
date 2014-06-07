<?php namespace Jumilla\LaravelExtension;

use Symfony\Component\Finder\Finder;
use Illuminate\Filesystem\Filesystem;

class ServiceProvider extends \Illuminate\Support\ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	private $plugins;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->plugins = PluginManager::plugins();

//		$this->loadAutoloadFiles(PluginManager::path());

		ClassResolver::register($this->plugins, $this->app['config']->get('app.aliases'));
	}

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('jumilla/laravel', 'jumilla', __DIR__);

		// Add package commands
		$this->setupCommands([
			['name' => 'package.setup', 'class' => 'Jumilla\\LaravelExtension\\Commands\\SetupCommand'],
			['name' => 'package.make', 'class' => 'Jumilla\\LaravelExtension\\Commands\\MakeCommand'],
// dump-autoload
// publish
		]);

		// setup all plugins
		foreach ($this->plugins as $plugin) {
			$this->setupPlugin($plugin);
		}
	}

	function setupPlugin($plugin)
	{
//		\Log::info($plugin->name());
		// register package
		$this->package('plugins/'.$plugin->name, $plugin->name, $plugin->path);

		// 
		$this->loadFiles($plugin);
	}

	function loadFiles($plugin)
	{
		$files = $this->app['files'];
		foreach ($files->files($plugin->path) as $file) {
			if (ends_with($file, '.php')) {
				require $file;
			}
		}
	}

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
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [];
	}

}
