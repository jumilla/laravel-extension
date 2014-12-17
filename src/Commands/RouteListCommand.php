<?php namespace LaravelPlus\Extension\Commands;

use Illuminate\Foundation\Application;
use Illuminate\Console\Command;
use Illuminate\Foundation\Console\RouteListCommand as BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class RouteListCommand extends BaseCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'route';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '[+] List all registered routes';

	/**
	 * Create a new route command instance.
	 *
	 * @param  \Illuminate\Foundation\Application  $app
	 * @return void
	 */
	public function __construct(Application $app)
	{
		parent::__construct($app['router']);
	}

}
