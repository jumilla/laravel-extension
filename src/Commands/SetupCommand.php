<?php namespace Jumilla\LaravelExtension\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Application;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Jumilla\LaravelExtension\PluginManager;

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
	protected $name = 'plugin:setup';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Setup plugin architecture.';

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

		// make plugins/
		$pluginsDirectory = PluginManager::path();
		if (!$this->files->exists($pluginsDirectory))
			$this->files->makeDirectory($pluginsDirectory);

		// copy app/config/plugin.php
		$pluginConfigSourceFile = __DIR__ . '/../../config/plugin.php';
		$pluginConfigFile = app_path().'/config/plugin.php';
		if (!$this->files->exists($pluginConfigFile))
			$this->files->copy($pluginConfigSourceFile, $pluginConfigFile);
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
