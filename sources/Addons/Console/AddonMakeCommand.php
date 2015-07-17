<?php

namespace LaravelPlus\Extension\Addons\Console;

use LaravelPlus\Extension\Addons\AddonDirectory;

/**
 * Modules console commands.
 * @author Fumio Furukawa <fumio.furukawa@gmail.com>
 */
class AddonMakeCommand extends AbstractCommand
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'addon:make
        {name : Name of addon.}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[+] Make addon';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // load laravel services
        $this->files =
            $files = $this->laravel['files'];
        $this->translator =
            $translator = $this->laravel['translator'];

        // load command arguments
        $addonName = $this->argument('name');
        $namespace = str_replace('/', '\\', $this->option('namespace'));
        if (empty($namespace)) {
            $namespace = ucfirst(studly_case($addonName));
        }
        if ($this->option('no-namespace')) {
            $namespace = '';
        }
        $locale = $translator->getLocale();

        $namespacePrefix = $namespace ? $namespace.'\\' : '';

        // output spec
        $this->line('== Making Addon Specs ==');
        $this->line(sprintf('Directory name: "%s"', $addonName));
        $this->line(sprintf('PHP namespace: "%s"', $namespace));

        $addonsDirectory = AddonDirectory::path();

        // make addons/
        if (!$files->exists($addonsDirectory)) {
            $files->makeDirectory($addonsDirectory);
        }

        $basePath = $this->basePath = $addonsDirectory.'/'.$addonName;

        if ($files->exists($basePath)) {
            $this->error(sprintf('Error: directory "%s" already exists.', $basePath));

            return;
        }

        $files->makeDirectory($basePath);

        $addonDirectorStructure = [
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
        ];
        if ($translator->getLocale() !== 'en') {
            $addonDirectorStructure[] = 'resources/lang/'.$translator->getLocale();
        }

        $this->makeDirectories($addonDirectorStructure);

        $arguments = compact('addonName', 'namespace', 'locale');

        $this->makeAddonJson($arguments);

        $this->makeConfigurations($arguments);

        $this->makeProviders($arguments);

        $this->makeConsoleCommands($arguments);

        $this->makeRoutes($arguments);

        $this->makeControllers($arguments);

        $this->makeViews($arguments);

        $this->makeTranslations($arguments);

        $this->makeDirectoryKeepFiles($addonDirectorStructure);

        $this->info('Addon Generated');
    }

    protected function makeAddonJson(array $arguments)
    {
        extract($arguments);

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
    }

    protected function makeConfigurations(array $arguments)
    {
        extract($arguments);
    }

    protected function makeProviders(array $arguments)
    {
        extract($arguments);

        // app/Providers/AddonServiceProvider.php
        $source = <<<SRC
use Illuminate\Support\ServiceProvider;

class AddonServiceProvider extends ServiceProvider {

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
    }

    protected function makeConsoleCommands(array $arguments)
    {
        extract($arguments);
    }

    protected function makeRoutes(array $arguments)
    {
        extract($arguments);

        // app/Providers/RouteServiceProvider.php
        $source = <<<SRC
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider {

	/**
	 * Routing group prefix.
	 *
	 * @var string
	 */
	protected \$prefix = 'addons/{$addonName}';

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
	}

	/**
	 * Define the routes for the addon.
	 *
	 * @param  \Illuminate\Routing\Router  \$router  (injection)
	 * @return void
	 */
	public function map(Router \$router)
	{
		\$router->group(['prefix' => \$this->prefix, 'namespace' => \$this->namespace], function(\$router)
		{
			require __DIR__ . '/../Http/routes.php';
		});
	}

}
SRC;
        $this->makePhpSource('app/Providers/RouteServiceProvider.php', $source, $namespace.'\\Providers');

        // app/Http/routes.php
        $source = <<<SRC
Route::get('', ['uses' => 'SampleController@index']);
SRC;
        $this->makePhpSource('app/Http/routes.php', $source);
    }

    protected function makeControllers(array $arguments)
    {
        extract($arguments);

        // app/Http/Controllers/BaseController.php
        $source = <<<SRC
use Illuminate\Routing\Controller;

class BaseController extends Controller {

	public function __construct()
	{
		View::share('__addon_name', addon_name());
	}

}
SRC;
        $this->makePhpSource('app/Http/Controllers/BaseController.php', $source, $namespace.'\\Http\\Controllers');

        // app/Http/Controllers/SampleController.php
        $source = <<<SRC
class SampleController extends BaseController {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		return addon_view(addon_name(), 'sample');
	}

}
SRC;
        $this->makePhpSource('app/Http/Controllers/SampleController.php', $source, $namespace.'\\Http\\Controllers');
    }

    protected function makeViews(array $arguments)
    {
        extract($arguments);

        // resources/views/sample.blade.php
        $source = <<<SRC
<h1>{{ addon_trans(\$__addon_name, 'messages.sample_title') }}</h1>
SRC;
        $this->makeTextFile('resources/views/sample.blade.php', $source);
    }

    protected function makeTranslations(array $arguments)
    {
        extract($arguments);

        $this->makePhpConfig('resources/lang/en/messages.php', [
            'sample_title' => 'Welcome addon: '.$addonName,
        ]);

        if ($arguments['locale'] !== 'en') {
            $this->makePhpConfig('resources/lang/'.$arguments['locale'].'/messages.php', [
                'sample_title' => 'アドオン '.$addonName.' へようこそ。',
            ]);
        }
    }
}
