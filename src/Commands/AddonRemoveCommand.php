<?php namespace LaravelPlus\Extension\Commands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Finder\Finder;
use LaravelPlus\Extension\Addons\AddonManager;

class AddonRemoveCommand extends AbstractCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'addon:remove';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '[+] Remove addon.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		// setup addon environment
		$this->call('addon:setup');

		$addonName = $this->argument('name');

		// check addon
		if (! AddonManager::exists($addonName)) {
			$this->error(sprintf('Addon "%s" is not found.', $addonName));
			return;
		}

		// confirm
		if (! $this->option('force')) {
			if (! $this->confirm('Are you sure? [y/N]', false)) {
				$this->comment('canceled');
				return;
			}
		}

		// process
		$this->files->deleteDirectory(AddonManager::path($addonName));

		$this->info(sprintf('Addon "%s" removed.', $addonName));
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['name', InputArgument::REQUIRED, 'Name of addon.'],
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
			['force', 'f', InputOption::VALUE_NONE, 'Force remove.', null],
		];
	}

}
