<?php namespace LaravelPlus\Extension\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Finder\Finder;
use LaravelPlus\Extension\Addons\AddonDirectory;

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
	protected $description = '[+] List up addon information';

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
	public function handle()
	{
		$files = $this->laravel['files'];

		// make addons/
		$addonsDirectory = AddonDirectory::path();
		if (!$files->exists($addonsDirectory)) {
			$files->makeDirectory($addonsDirectory);
		}

		$addons = AddonDirectory::addons();
		foreach ($addons as $addon) {
			$this->dump($addon);
		}
	}

	protected function dump($addon)
	{
		$this->output->writeln($addon->name());
	}

}
