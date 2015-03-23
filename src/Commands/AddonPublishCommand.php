<?php namespace LaravelPlus\Extension\Commands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use LaravelPlus\Extension\Addons\AddonManager;
use LaravelPlus\Extension\Addons\Addon;

class AddonPublishCommand extends AbstractCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'addon:publish';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '[+] Publish addon migrations & assets.';

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['name', InputArgument::OPTIONAL, 'Name of addon.'],
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

		$name = $this->argument('name');
		if ($name) {
			$addon = AddonManager::addon($name);
			if (! $addon) {
				$this->error(sprintf('Addon "%s" not found.', $name));
				return;
			}
			$addons = [];
		}
		else {
			$addons = AddonManager::addons();
		}

		foreach ($addons as $addon) {
			$this->publish($addon);
		}
	}

	/**
	 * @param \LaravelPlus\Extension\Addons\Addon $addon
	 */
	protected function publish(Addon $addon)
	{
		$this->publishMigrations($addon);

		$this->publishAssets($addon);

		$this->info(sprintf('Addon "%s" published.', $addon->name));
	}

	/**
	 * @param \LaravelPlus\Extension\Addons\Addon $addon
	 */
	protected function publishMigrations(Addon $addon)
	{
		$srcPath = $addon->path . '/migrations';
		$destPath = $this->laravel['path'] . '/database/migrations';

		$this->files->copyDirectory($srcPath, $destPath);
	}

	/**
	 * @param \LaravelPlus\Extension\Addons\Addon $addon
	 */
	protected function publishAssets(Addon $addon)
	{
		$srcPath = $addon->path . '/assets';
		$destPath = $this->laravel['path.public'] . '/assets';

		$this->files->copyDirectory($srcPath, $destPath);
	}

}
