<?php namespace Jumilla\LaravelExtension\Commands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Jumilla\LaravelExtension\PluginManager;

/**
* Modules console commands
* @author Fumio Furukawa <fumio.furukawa@gmail.com>
*/
class PluginMakeCommand extends AbstractCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'plugin:make';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Make plugin.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		// load laravel services
		$files = $this->laravel['files'];
		$translator = $this->laravel['translator'];

		// load command arguments
		$pluginName = $this->argument('name');
		$namespace = $this->option('namespace');
		if (empty($namespace))
			$namespace = ucfirst(\Str::studly($pluginName));

		$pluginsDirectory = PluginManager::path();
//		$templateDirectory = dirname(dirname(__DIR__)).'/templates/plugin';

		// make plugins/
		if (!$files->exists($pluginsDirectory))
			$files->makeDirectory($pluginsDirectory);

		$basePath = $this->basePath = $pluginsDirectory.'/'.$pluginName;

		if ($files->exists($basePath)) {
			echo 'Error: already exists.';
			return;
		}

		$files->makeDirectory($basePath);

		$this->makeDirectories([
			'assets',
			'config',
			'controllers',
			'lang',
			'lang/en',
			'migrations',
			'models',
			'services',
			'views',
		]);
		if ($translator->getLocale() !== 'en') {
			$this->makeDirectories([
				'lang/'.$translator->getLocale(),
			]);
		}

/*
		$this->makeComposerJson($namespace, [
			'controllers',
			'migrations',
			'models',
		]);
*/

		$this->makePhpConfig('config/config.php', [
			'sample_title' => 'Plugin: '.$pluginName,
		]);
		$this->makePhpConfig('config/plugin.php', [
			'namespace' => $namespace,
			'directories' => [
				'controllers',
				'migrations',
				'models',
				'services',
			],
			'providers' => [
			],
			'includes_global_aliases' => true,
			'aliases' => [
			],
		]);

		// controllers/BaseController.php
		$source = <<<SRC
class BaseController extends \Controller {

}
SRC;
		$this->makePhpSource('controllers/BaseController.php', $source, $namespace);

		// controllers/SampleController.php
		$source = <<<SRC
class SampleController extends BaseController {

	public function index() {
		return View::make('{$pluginName}::sample');
	}

}
SRC;
		$this->makePhpSource('controllers/SampleController.php', $source, $namespace);

		// controllers/SampleController.php
		$source = <<<SRC

class ServiceProvider extends \Illuminate\Support\ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected \$defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
	}

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
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
SRC;
		$this->makePhpSource('services/ServiceProvider.php', $source, $namespace);

		// views/sample.blade.php
		$source = <<<SRC
<h1>{{ Config::get('{$pluginName}::sample_title') }}</h1>
SRC;
		$this->makeTextFile('views/sample.blade.php', $source);

		// routes.php
		$source = <<<SRC
Route::get('plugins/{$pluginName}', ['uses' => '{$namespace}\SampleController@index']);
SRC;
		$this->makePhpSource('routes.php', $source);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['name', InputArgument::REQUIRED, 'Plugin name.'],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			['namespace', null, InputOption::VALUE_OPTIONAL, 'Plugin namespace.', null],
		];
	}

}
