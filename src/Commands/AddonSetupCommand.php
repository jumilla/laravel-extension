<?php namespace Jumilla\LaravelExtension\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Application;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Jumilla\LaravelExtension\Addons\AddonDirectory;

/**
* Modules console commands
* @author Fumio Furukawa <fumio.furukawa@gmail.com>
*/
class AddonSetupCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'addon:setup';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Setup addon architecture.';

	/**
	 * IoC
	 *
	 * @var Illuminate\Filesystem\Filesystem
	 */
	protected $files;

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$this->files = $this->laravel['files'];

		// make addons/
		$addonsDirectory = AddonDirectory::path();
		if (!$this->files->exists($addonsDirectory))
			$this->files->makeDirectory($addonsDirectory);

		// copy app/config/addon.php
		$addonConfigSourceFile = __DIR__ . '/../../config/addon.php';
		$addonConfigFile = app('path.config').'/addon.php';
		if (!$this->files->exists($addonConfigFile))
			$this->files->copy($addonConfigSourceFile, $addonConfigFile);
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
