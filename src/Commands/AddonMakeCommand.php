<?php namespace LaravelPlus\Extension\Commands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use LaravelPlus\Extension\Addons\AddonManager;

/**
* Modules console commands
* @author Fumio Furukawa <fumio.furukawa@gmail.com>
*/
class AddonMakeCommand extends AbstractCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'addon:make';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '[+] Make addon.';

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['name', InputArgument::REQUIRED, 'Addon name.'],
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
			['namespace', null, InputOption::VALUE_OPTIONAL, 'Addon namespace.', null],
			['no-namespace', null, InputOption::VALUE_NONE, 'Addon namespace nothing.', null],
		];
	}

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

		// setup addon environment
		$this->call('addon:setup');

		// load command arguments
		$addonName = $this->argument('name');
		$namespace = str_replace('/', '\\', $this->option('namespace'));
		if (empty($namespace))
			$namespace = ucfirst(studly_case($addonName));
		if ($this->option('no-namespace'))
			$namespace = '';

		$namespacePrefix = $namespace ? $namespace.'\\' : '';

		// output spec
		$this->line('== Making Addon Specs ==');
		$this->line(sprintf('Directory name: "%s"', $addonName));
		$this->line(sprintf('PHP namespace: "%s"', $namespace));

		$addonsDirectory = AddonManager::path();

		$basePath = $this->basePath = $addonsDirectory.'/'.$addonName;

		if ($files->exists($basePath)) {
			$this->error(sprintf('Error: directory "%s" already exists.', $basePath));
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
			'specs',
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
			'sample_title' => 'Addon: '.$addonName,
		]);
		$this->makePhpConfig('config/addon.php', [
			'version' => 4,
			'namespace' => $namespace,
			'directories' => [
				'controllers',
				'models',
				'services',
			],
			'files' => [
				'routes.php',
			],
			'providers' => [
			],
			'includes_global_aliases' => true,
			'aliases' => [
			],
		]);

		// controllers/BaseController.php
		$source = <<<SRC
use Illuminate\Routing\Controller;

class BaseController extends Controller {

}
SRC;
		$this->makePhpSource('controllers/BaseController.php', $source, $namespace);

		// controllers/SampleController.php
		$source = <<<SRC
class SampleController extends BaseController {

	public function index()
	{
		Log::debug(addon_name() . ' sample');
		return View::make(addon_namespace() . 'sample');
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
<h1>{{ Config::get('{$addonName}::sample_title') }}</h1>
SRC;
		$this->makeTextFile('views/sample.blade.php', $source);

		// routes.php
		$source = <<<SRC
Route::get('addons/{$addonName}', ['uses' => '{$namespacePrefix}SampleController@index']);
SRC;
		$this->makePhpSource('routes.php', $source);

		$this->info('Addon Generated');
	}

}
