<?php namespace LaravelPlus\Extension\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
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
	protected $description = '[+] Make addon';

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
	public function handle()
	{
		// load laravel services
		$files = $this->laravel['files'];
		$translator = $this->laravel['translator'];

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

		$addonsDirectory = AddonDirectory::path();

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
			'app',
			'app/Console',
			'app/Console/Commands',
			'app/Http',
			'app/Http/Controllers',
			'app/Http/Middleware',
			'app/Http/Requests',
			'app/Providers',
			'app/Services',
			'config',
			'database',
			'database/migrations',
			'database/seeds',
			'resources',
			'resources/assets',
			'resources/lang',
			'resources/lang/en',
			'resources/specs',
			'resources/views',
			'tests',
		]);
		if ($translator->getLocale() !== 'en') {
			$this->makeDirectories([
				'resources/lang/'.$translator->getLocale(),
			]);
		}

		$this->makeJson('addon.json', [
			'version' => 5,
			'namespace' => $namespace,
			'directories' => [
				'app',
			],
			'paths' => [
				'migrations' => 'database/migrations',
				'assets' => 'resources/assets',
				'lang' => 'resources/lang',
				'specs' => 'resources/specs',
				'views' => 'resources/views',
				'tests' => 'tests',
			],
			'providers' => [
				$namespace.'\\Providers\\AddonServiceProvider',
				$namespace.'\\Providers\\RouteServiceProvider',
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

		$this->makePhpConfig('resources/lang/en/messages.php', [
			'sample_title' => 'Addon: '.$addonName,
		]);

		// app/Http/Controllers/BaseController.php
		$source = <<<SRC
use Illuminate\Routing\Controller;

class BaseController extends Controller {

}
SRC;
		$this->makePhpSource('app/Http/Controllers/BaseController.php', $source, $namespace.'\\Http\\Controllers');

		// controllers/Http/Controllers/SampleController.php
		$source = <<<SRC
class SampleController extends BaseController {

	public function index()
	{
		return addon_view(addon_namespace(), 'sample');
	}

}
SRC;
		$this->makePhpSource('app/Http/Controllers/SampleController.php', $source, $namespace.'\\Http\\Controllers');

		// app/Providers/AddonServiceProvider.php
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
		$this->makePhpSource('app/Providers/AddonServiceProvider.php', $source, $namespace.'\\Providers');

		// app/Providers/RouteServiceProvider.php
		$source = <<<SRC

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider {

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
		$this->makePhpSource('app/Providers/RouteServiceProvider.php', $source, $namespace.'\\Providers');

		// resources/views/sample.blade.php
		$source = <<<SRC
<h1>{{ addon_trans(addon_name(), 'messages.sample_title') }}</h1>
SRC;
		$this->makeTextFile('resources/views/sample.blade.php', $source);

		// app/Http/routes.php
		$source = <<<SRC
Route::get('addons/{$addonName}', ['uses' => 'SampleController@index']);
SRC;
		$this->makePhpSource('app/Http/routes.php', $source);

		$this->info('Addon Generated');
	}

}
