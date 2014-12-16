<?php namespace LaravelPlus\Extension\Commands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use LaravelPlus\Extension\Addons\AddonDirectory;

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
	protected $description = '[+]Make addon.';

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
		$addonName = $this->argument('name');
		$namespace = $this->option('namespace');
		if (empty($namespace))
			$namespace = ucfirst(studly_case($addonName));
		if ($this->option('no-namespace'))
			$namespace = '';

		$namespacePrefix = $namespace ? $namespace.'\\' : '';

		// output spec
		$this->line('== Making Addon Specs ==');
		$this->line(sprintf('Directory name: "%s"', $addonName));
		$this->line(sprintf('PHP namespace: "%s"', $namespace));

		$addonsDirectory = AddonDirectory::path();
//		$templateDirectory = dirname(dirname(__DIR__)).'/templates/addon';

		// make addons/
		if (!$files->exists($addonsDirectory))
			$files->makeDirectory($addonsDirectory);

		$basePath = $this->basePath = $addonsDirectory.'/'.$addonName;

		if ($files->exists($basePath)) {
			$this->error(sprintf('Error: directory "%s" already exists.', $basePath));
			return;
		}

		$files->makeDirectory($basePath);

		$this->makeDirectories([
			'assets',
			'classes',
			'classes/Console',
			'classes/Console/Commands',
			'classes/Http',
			'classes/Http/Controllers',
			'classes/Http/Middleware',
			'classes/Http/Requests',
			'classes/Providers',
			'classes/Services',
			'config',
			'database',
			'database/migrations',
			'database/seeds',
			'lang',
			'lang/en',
			'specs',
			'templates',
			'tests',
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

		$this->makePhpConfig('lang/en/messages.php', [
			'sample_title' => 'Addon: '.$addonName,
		]);
		$this->makePhpConfig('config/addon.php', [
			'version' => 5,
			'namespace' => $namespace,
			'directories' => [
				'classes',
				'database/seeds',
			],
			'paths' => [
				'assets' => 'assets',
				'lang' => 'lang',
				'templates' => 'templates',
				'migrations' => 'database/migrations',
				'seeds' => 'database/seeds',
				'specs' => 'specs',
				'tests' => 'tests',
			],
			'providers' => [
				$namespace.'\Providers\AddonServiceProvider',
				$namespace.'\Providers\RouteServiceProvider',
			],
			'console' => [
				'commands' => [
				],
			],
			'http' => [
				'middlewares' => [
				],
				'route_middlewares' => [
				],
			],
			'includes_global_aliases' => true,
			'aliases' => [
			],
		]);

		// classes/Http/Controllers/BaseController.php
		$source = <<<SRC
use Illuminate\Routing\Controller;

class BaseController extends Controller {

}
SRC;
		$this->makePhpSource('classes/Http/Controllers/BaseController.php', $source, $namespace.'\\Http\\Controllers');

		// controllers/Http/Controllers/SampleController.php
		$source = <<<SRC
class SampleController extends BaseController {

	public function index()
	{
		return View::make('{$addonName}::sample');
	}

}
SRC;
		$this->makePhpSource('classes/Http/Controllers/SampleController.php', $source, $namespace.'\\Http\\Controllers');

		// classes/Providers/AddonServiceProvider.php
		$source = <<<SRC

class AddonServiceProvider extends \Illuminate\Support\ServiceProvider {

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
		$this->makePhpSource('classes/Providers/AddonServiceProvider.php', $source, $namespace.'\\Providers');

		// classes/Providers/RouteServiceProvider.php
		$source = <<<SRC

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider {

	/**
	 * All of the application's route middleware keys.
	 *
	 * @var array
	 */
	protected \$middleware = [
	];

	protected \$scan = [
	];
//	protected \$scanWhenLocal = true;

	/**
	 * This namespace is applied to the controller routes in your routes file.
	 *
	 * In addition, it is set as the URL generator's root namespace.
	 *
	 * @var string
	 */
	protected \$namespace = '{$namespace}\Http\Controllers';

	/**
	 * Define your route model bindings, pattern filters, etc.
	 *
	 * @param  \Illuminate\Routing\Router  \$router
	 * @return void
	 */
	public function boot(Router \$router)
	{
		parent::boot(\$router);

		//
	}

	/**
	 * Define the routes for the addon.
	 *
	 * @param  \Illuminate\Routing\Router  \$router  (injection)
	 * @return void
	 */
	public function map(Router \$router)
	{
		\$router->group(['prefix' => '', 'namespace' => \$this->namespace], function(\$router)
		{
			require __DIR__ . '/../Http/routes.php';
		});
	}

}
SRC;
		$this->makePhpSource('classes/Providers/RouteServiceProvider.php', $source, $namespace.'\\Providers');

		// templates/sample.blade.php
		$source = <<<SRC
<h1>{{ addon_trans('{$addonName}', 'messages.sample_title') }}</h1>
SRC;
		$this->makeTextFile('templates/sample.blade.php', $source);

		// routes.php
		$source = <<<SRC
Route::get('addons/{$addonName}', ['uses' => 'SampleController@index']);
SRC;
		$this->makePhpSource('classes/Http/routes.php', $source);

		$this->info('Addon Generated');
	}

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

}
