<?php namespace Jumilla\LaravelExtension\Commands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Jumilla\LaravelExtension\PluginManager;

/**
* Modules console commands
* @author Fumio Furukawa <fumio.furukawa@gmail.com>
*/
class MakeCommand extends AbstractCommand {

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
		$files = $this->files = $this->laravel['files'];
		$pluginsDirectory = PluginManager::path();
//		$templateDirectory = dirname(dirname(__DIR__)).'/templates/plugin';

		$pluginName = $this->argument('name');

		// make packages/
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
			'lang/ja',
			'migrations',
			'models',
			'views',
		]);

		$namespace = ucfirst(\Str::studly($pluginName));
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
		$this->makePhpConfig('config/package.php', [
			'namespace' => $namespace,
			'directories' => [
				'controllers',
				'migrations',
				'models',
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
//			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		];
	}

}
