<?php namespace Jumilla\Laravel;

use Symfony\Component\Finder\Finder;
use Illuminate\Filesystem\Filesystem;

class ServiceProvider extends \Illuminate\Support\ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	private $packages;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->packages = PackageCollection::packages();

//		$this->loadAutoloadFiles(PackageCollection::path());

		ClassResolver::register($this->packages, $this->app['config']->get('app.aliases'));
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
			['name' => 'package.setup', 'class' => 'Jumilla\\Laravel\\Commands\\SetupCommand'],
			['name' => 'package.make', 'class' => 'Jumilla\\Laravel\\Commands\\MakeCommand'],
// dump-autoload
// publish
		]);

		// setup all packages
		foreach ($this->packages as $package) {
			$this->setupEmbedPackage($package);
		}
	}

	function setupEmbedPackage($package)
	{
//		\Log::info($package->name());
		// register package
		$this->package('packages/'.$package->name, $package->name, $package->path);

		// 
		$this->loadFiles($package);
	}

	function loadFiles($package)
	{
		$files = $this->app['files'];
		foreach ($files->files($package->path) as $file) {
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
