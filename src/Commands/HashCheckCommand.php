<?php namespace LaravelPlus\Extension\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Application;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
* Modules console commands
* @author Fumio Furukawa <fumio.furukawa@gmail.com>
*/
class HashCheckCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'hash:check';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '[+]Check hashed value.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$cost = $this->option('cost');

		$string1 = $this->argument('string1');
		$string2 = $this->argument('string2');

		if ($this->isHashed($string1)) {
			$rawString = $string2;
			$hashedString = $string1;
		}
		elseif ($this->isHashed($string2)) {
			$rawString = $string1;
			$hashedString = $string2;
		}
		else {
			$this->error(sprintf('Error: both "%s" and "%s" are not hashed string.', $string1, $string2));
			return;
		}


		$result = app('hash')->check($string1, $string2);

		$this->info($result ? 'Check OK!' : 'Check NG.');
	}

	protected function isHashed($string)
	{
		return strlen($string) == 60 && starts_with($string, '$2y$');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['string1', InputArgument::REQUIRED, 'Plain or Hashed string.'],
			['string2', InputArgument::REQUIRED, 'Plain or Hashed string.'],
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
