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
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		// setup addon environment
		$this->call('addon:setup');

		$this->output->writeln('> Check Start.');
		$this->output->writeln('--------');

		$addons = AddonManager::addons();
		foreach ($addons as $addon) {
			$this->dump($addon);
		}

		$this->output->writeln('> Check Finished!');
	}

	protected function dump($addon)
	{
		$this->dumpProperties($addon);
		$this->dumpClasses($addon);
		$this->dumpServiceProviders($addon);

		$this->output->writeln('--------');
	}

	protected function dumpProperties($addon)
	{
		$this->info(sprintf('Addon "%s"', $addon->name));
		$this->info(sprintf('Path: %s', $addon->relativePath()));
		$this->info(sprintf('PHP namespace: %s', $addon->config('namespace')));
	}

	protected function dumpClasses($addon)
	{
		// load laravel services
		$files = $this->laravel['files'];

		// 全ディレクトリ下を探索する (PSR-4)
		foreach ($addon->config('directories') as $directory) {
			$this->info(sprintf('PHP classes on "%s"', $directory));

			$classDirectoryPath = $addon->path.'/'.$directory;

			if (!file_exists($classDirectoryPath)) {
				$this->line(sprintf('Warning: Class directory "%s" not found', $directory));
				continue;
			}

			// recursive find files
			$phpFilePaths = iterator_to_array((new Finder)->in($classDirectoryPath)->name('*.php')->files(), false);

			foreach ($phpFilePaths as $phpFilePath) {
				$relativePath = substr($phpFilePath, strlen($classDirectoryPath) + 1);

				$classFullName = $addon->config('namespace').'\\'.AddonManager::pathToClass($relativePath);

				$this->line(sprintf('  "%s" => %s', $relativePath, $classFullName));
			}
		}
	}

	protected function dumpServiceProviders($addon)
	{
		
	}

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

}
