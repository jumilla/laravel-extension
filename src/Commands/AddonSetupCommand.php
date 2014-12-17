<?php namespace LaravelPlus\Extension\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use LaravelPlus\Extension\Addons\AddonDirectory;

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
	protected $description = '[+] Setup addon architecture';

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

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$files = $this->laravel['files'];

		// make addons/
		$addonsDirectory = AddonDirectory::path();
		if (!$files->exists($addonsDirectory)) {
			$files->makeDirectory($addonsDirectory);

			$this->info('make directory: ' . $addonsDirectory);
		}

		// copy app/config/addon.php
		$addonConfigSourceFile = __DIR__ . '/../../config/addon.php';
		$addonConfigFile = app('path.config').'/addon.php';
		if (!$files->exists($addonConfigFile)) {
			$files->copy($addonConfigSourceFile, $addonConfigFile);

			$this->info('make config: ' . $addonConfigFile);
		}

		$this->info('Setup Succeeded.');
	}

}
