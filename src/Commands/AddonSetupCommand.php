<?php namespace LaravelPlus\Extension\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Application;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use LaravelPlus\Extension\Addons\AddonManager;

/**
* Modules console commands
* @author Fumio Furukawa <fumio.furukawa@gmail.com>
*/
class AddonSetupCommand extends AbstractCommand {

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
	protected $description = '[+] Setup addon architecture.';

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
//			['example', InputArgument::REQUIRED, 'An example argument.'],
//			['example', InputArgument::OPTION, 'An example argument.', 'option value'],
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
//			['example', null, InputOption::VALUE_NONE, 'An example option.', null],
//			['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
		];
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		// make addons/
		$addonsDirectory = AddonManager::path();
		if (!$this->files->exists($addonsDirectory)) {
			$this->files->makeDirectory($addonsDirectory);
		}

		// copy app/config/addon.php
		$templateConfigFile = __DIR__ . '/../../config/addon.php';
		$addonConfigFile = app('path').'/config/addon.php';
		if (!$this->files->exists($addonConfigFile)) {
			$this->files->copy($templateConfigFile, $addonConfigFile);
		}
	}

}
