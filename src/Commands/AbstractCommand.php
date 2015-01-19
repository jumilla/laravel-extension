<?php namespace LaravelPlus\Extension\Commands;

use Illuminate\Console\Command;
use LaravelPlus\Extension\Generators\PhpSettingGenerator;

abstract class AbstractCommand extends Command {

	protected $files;

	public function setLaravel($laravel)
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

	protected function makeComposerJson($namespace, $subPaths)
	{
		$data = [
			'autoload' => [
				'psr-4' => [$namespace.'\\' => $subPaths],
			],
		];
		$this->files->prepend($this->basePath.'/composer.json', json_encode($data, JSON_PRETTY_PRINT));
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
