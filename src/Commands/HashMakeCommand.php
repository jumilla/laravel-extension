<?php namespace LaravelPlus\Extension\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Application;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
* Modules console commands
* @author Fumio Furukawa <fumio.furukawa@gmail.com>
*/
class HashMakeCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hash:make';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '[+]Make hashed value.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$cost = $this->option('cost');

		$hashed = app('hash')->make($this->argument('string'), [
			'rounds' => $cost,
		]);

		$this->info(sprintf('Generated hash: "%s"', $hashed));
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['string', InputArgument::REQUIRED, 'Plain string.'],
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
			['cost', 'c', InputOption::VALUE_OPTIONAL, 'Generate cost.', 10],
		];
	}

}
