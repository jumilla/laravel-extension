<?php namespace LaravelPlus\Extension\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Application;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use LaravelPlus\Extension\Addons\Addon;
use LaravelPlus\Extension\Addons\AddonDirectory;

/**
* Modules console commands
* @author Fumio Furukawa <fumio.furukawa@gmail.com>
*/
class AddonMigrateRunCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'addon:migrate:run';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Run migration for addon.';

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
		$addonName = $this->argument('name');

		$addonPath = AddonDirectory::path().'/'.$addonName;

		$addon = Addon::create($addonPath);

		$addonNamespace = $addon->config('namespace');

		$addonMigrationsPath = $addonPath . '/' . $addon->config('paths.migrations');

		$this->call('migrate', [
			'--path' => $addonMigrationsPath,
		]);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['name', InputArgument::REQUIRED, 'Addon name.'],
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
