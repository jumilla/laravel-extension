<?php namespace Jumilla\LaravelExtension\Commands;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Finder\Finder;
use Jumilla\LaravelExtension\PluginManager;

class PluginCheckCommand extends AbstractCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'plugin:check';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Check plugin information.';

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

		$this->output->writeln('> Check Start.');
		$this->output->writeln('--------');

		$plugins = PluginManager::plugins();
		foreach ($plugins as $plugin) {
			$this->dump($plugin);
		}

		$this->output->writeln('> Check Finished!');
	}

	function dump($plugin)
	{
		$this->dumpProperties($plugin);
		$this->dumpClasses($plugin);
		$this->dumpServiceProviders($plugin);

		$this->output->writeln('--------');
	}

	function dumpProperties($plugin)
	{
		$this->info(sprintf('Plugin "%s"', $plugin->name));
		$this->info(sprintf('Path: %s', $plugin->relativePath()));
		$this->info(sprintf('PHP namespace: %s', $plugin->config('namespace')));
	}

	function dumpClasses($plugin)
	{
		// load laravel services
		$files = $this->laravel['files'];
		$finder = new Finder;

		// 全ディレクトリ下を探索する (PSR-4)
		foreach ($plugin->config('directories') as $directory) {
			$this->info(sprintf('PHP classes on "%s"', $directory));

			$classDirectoryPath = $plugin->path.'/'.$directory;

			if (!file_exists($classDirectoryPath)) {
				$this->line(sprintf('Warning: Class directory "%s" not found', $directory));
				continue;
			}

			// recursive find files
			$phpFilePaths = iterator_to_array($finder->name('*.php')->files()->in($classDirectoryPath), false);

			foreach ($phpFilePaths as $phpFilePath) {
				$relativePath = substr($phpFilePath, strlen($classDirectoryPath) + 1);

				$classFullName = $plugin->config('namespace').'\\'.PluginManager::pathToClass($relativePath);

				$this->line(sprintf('  "%s" => %s', $relativePath, $classFullName));
			}
		}
	}

	function dumpServiceProviders($plugin)
	{
		
	}

}
