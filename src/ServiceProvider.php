<?php namespace Jumilla\LaravelExtension;

use Symfony\Component\Finder\Finder;

class ServiceProvider extends \Illuminate\Support\ServiceProvider {

	private static $commands = [
		['name' => 'commands.package.setup', 'class' => 'Jumilla\LaravelExtension\Commands\PluginSetupCommand'],
		['name' => 'commands.package.make', 'class' => 'Jumilla\LaravelExtension\Commands\PluginMakeCommand'],
		['name' => 'commands.package.check', 'class' => 'Jumilla\LaravelExtension\Commands\PluginCheckCommand'],
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

	private $plugins;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->plugins = PluginManager::plugins();

		// MEMO 現在はクラスファイルの解決を動的に行うモードのみ実装している。
//		$this->loadAutoloadFiles(PluginManager::path());

		PluginClassLoader::register($this->plugins);
		AliasResolver::register($this->plugins, $this->app['config']->get('app.aliases'));
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

		// setup all plugins
		$this->bootPlugins();
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
	 * setup & boot plugins.
	 *
	 * @return void
	 */
	function bootPlugins()
	{
		foreach ($this->plugins as $plugin) {
			$this->bootPlugin($plugin);
		}
	}

	/**
	 * setup & boot plugin.
	 *
	 * @param  $plugin \Jumilla\LaravelExtension\Plugin
	 * @return void
	 */
	function bootPlugin($plugin)
	{
		// regist package
		$this->package('plugins/'.$plugin->name, $plugin->name, $plugin->path);

		// regist service providers
		$providers = $plugin->config('providers', []);
		foreach ($providers as $provider) {
			if (!starts_with($provider, '\\'))
				$provider = sprintf('%s\%s', $plugin->config('namespace'), $provider);

			$this->app->register($provider);
		}

		// load *.php on plugin's root directory
		$this->loadFiles($plugin);
	}

	/**
	 * load plugin initial script files.
	 *
	 * @param  $plugin \Jumilla\LaravelExtension\Plugin
	 * @return void
	 */
	function loadFiles($plugin)
	{
		$files = $this->app['files'];
		foreach ($files->files($plugin->path) as $file) {
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
