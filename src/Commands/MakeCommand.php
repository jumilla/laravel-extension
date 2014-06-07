<?php namespace Jumilla\Laravel\Commands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Jumilla\Laravel\PackageCollection;

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
	protected $name = 'package:make';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$files = $this->files = $this->laravel['files'];
		$packagesDirectory = PackageCollection::path();
		$templateDirectory = dirname(dirname(__DIR__)).'/templates/package';

		$packageName = $this->argument('name');

		// make packages/
		if (!$files->exists($packagesDirectory))
			$files->makeDirectory($packagesDirectory);

		// make app/config/package.php
		$basePath = $this->basePath = $packagesDirectory.'/'.$packageName;

		if ($files->exists($basePath)) {
			return;
		}

		$files->makeDirectory($basePath);

		$this->makeDirectories([
			'config',
			'controllers',
			'lang',
			'migrations',
			'models',
			'views',
		]);

		$namespace = ucfirst(\Str::studly($packageName));
/*
		$this->makeComposerJson($namespace, [
			'controllers',
			'migrations',
			'models',
		]);
*/

		$this->makePhpConfig('config/config.php', [
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

		// controllers/SampleController.php
		$source = <<<SRC
class SampleController extends Controller {

	public function index() {
		return View::make('{$packageName}::sample');
	}

}
SRC;
		$this->makePhpSource('controllers/SampleController.php', $source, $namespace);

		// views/sample.blade.php
		$source = <<<SRC
<h1>Package: {$packageName}</h1>
SRC;
		$this->makeTextFile('views/sample.blade.php', $source);

		// routes.php
		$source = <<<SRC
Route::get('packages/{$packageName}', ['uses' => '{$namespace}\SampleController@index']);
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
			['name', InputArgument::REQUIRED, 'Package name.'],
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
