<?php namespace Jumilla\LaravelExtension\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Application;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Jumilla\LaravelExtension\Hooks\ApplicationHook;

/**
* @author Fumio Furukawa <fumio.furukawa@gmail.com>
*/
class AppContainerListCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'app:container:list';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Setup addon architecture.';

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
		$app = new ApplicationHook(app());

		$objects = $app->getInstances();
		$aliases = [];

		foreach ($app->getAliases() as $alias => $abstract) {
			$aliases[$abstract][] = $alias;
		}

		ksort($objects);

		foreach ($objects as $name => $instance) {
			$this->info($name.': ');

			$this->output($instance);

			if (isset($aliases[$name])) {
				foreach ($aliases[$name] as $value) {
					echo "\t", '[alias] "', $value, '"', "\n";
				}
			}
		}
	}

	private function output($instance)
	{
		echo "\t";
		if (is_object($instance))
			echo 'object "', get_class($instance), '"';
		else if (is_string($instance))
			echo 'string "', $instance, '"';
		else if (is_bool($instance))
			echo 'bool ', $instance, '';
		else 
			echo '(unknown) ', $instance, '';
		echo "\n";
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
