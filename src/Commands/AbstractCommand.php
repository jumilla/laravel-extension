<?php namespace LaravelPlus\Extension\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application as LaravelApplication;
use LaravelPlus\Extension\Generators\PhpSettingGenerator;

abstract class AbstractCommand extends Command {

	protected $files;

	/**
	 * Set the Laravel application instance.
	 *
	 * @param  \Illuminate\Contracts\Foundation\Application  $laravel
	 * @return void
	 */
	public function setLaravel(LaravelApplication $laravel)
	{
		parent::setLaravel($laravel);

		$this->files = 	$this->laravel['files'];
	}

	protected function makeDirectories($subPaths)
	{
		foreach ($subPaths as $path) {
			$this->files->makeDirectory($this->basePath.'/'.$path);
		}
	}

	protected function makeJson($path, array $data)
	{
		$this->files->prepend($this->basePath.'/'.$path, json_encode($data, JSON_PRETTY_PRINT));
	}

	protected function makePhpConfig($path, array $data)
	{
		$this->files->prepend($this->basePath.'/'.$path, PhpSettingGenerator::generateText($data));
	}

	protected function makePhpSource($path, $source, $namespace = null)
	{
		if ($namespace) {
			$namespace = "namespace {$namespace};";
		}
		$this->files->prepend($this->basePath.'/'.$path, "<?php {$namespace}\n\n{$source}\n");
	}

	protected function makeTextFile($path, $text)
	{
		$this->files->prepend($this->basePath.'/'.$path, "{$text}\n");
	}

}
