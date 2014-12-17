<?php namespace LaravelPlus\Extension\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use LaravelPlus\Extension\Addons\AddonDirectory;

class AddonCheckCommand extends AbstractCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'addon:check';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '[+] Check addon information';

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
		if (!$this->files->exists($addonsDirectory)) {
			$this->files->makeDirectory($addonsDirectory);
		}

		$this->output->writeln('> Check Start.');
		$this->output->writeln('--------');

		$addons = AddonDirectory::addons();
		foreach ($addons as $addon) {
			$this->dump($addon);
		}

		$this->output->writeln('> Check Finished!');
	}

	function dump($addon)
	{
		$this->dumpProperties($addon);
		$this->dumpClasses($addon);
		$this->dumpServiceProviders($addon);

		$this->output->writeln('--------');
	}

	function dumpProperties($addon)
	{
		$this->info(sprintf('Addon "%s"', $addon->name));
		$this->info(sprintf('Path: %s', $addon->relativePath()));
		$this->info(sprintf('PHP namespace: %s', $addon->config('addon.namespace')));
	}

	function dumpClasses($addon)
	{
		// load laravel services
		$files = $this->laravel['files'];

		// 全ディレクトリ下を探索する (PSR-4)
		foreach ($addon->config('addon.directories') as $directory) {
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

				$classFullName = $addon->config('addon.namespace').'\\'.AddonDirectory::pathToClass($relativePath);

				$this->line(sprintf('  "%s" => %s', $relativePath, $classFullName));
			}
		}
	}

	function dumpServiceProviders($addon)
	{
		
	}

}
