<?php namespace Jumilla\LaravelExtension\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Application;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
* Modules console commands
* @author Fumio Furukawa <fumio.furukawa@gmail.com>
*/
class SetupCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'package:setup';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	/**
	 * IoC
	 *
	 * @var Illuminate\Filesystem\Filesystem
	 */
	protected $files;

	/**
	 * DI
	 *
	 * @param Filesystem $files
	 */
	public function __construct(Application $app)
	{
		parent::__construct();
		$this->files = $app['files'];
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		// make packages/
		$packagesDirectory = base_path().'/packages';
		if (!$this->files->exists($packagesDirectory))
			$this->files->makeDirectory($packagesDirectory);

		// copy app/config/package.php
		$packageConfigSourceFile = __DIR__ . '/../../config/package.php';
		$packageConfigFile = app_path().'/config/package.php';
		if (!$this->files->exists($packageConfigFile))
			$this->files->copy($packageConfigSourceFile, $packageConfigFile);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
//			array('example', InputArgument::REQUIRED, 'An example argument.'),
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
