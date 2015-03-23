<?php namespace LaravelPlus\Extension\Commands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Finder\Finder;
use LaravelPlus\Extension\Addons\AddonManager;

class AddonListCommand extends AbstractCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'addon:list';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '[+] List up addon information.';

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
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
		];
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		// setup addon environment
		$this->call('addon:setup');

		$addons = AddonManager::addons();
		foreach ($addons as $addon) {
			$this->dump($addon);
		}
	}

	protected function dump($addon)
	{
		$this->output->writeln($addon->name);
	}

}
