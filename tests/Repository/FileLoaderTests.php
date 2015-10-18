<?php

use LaravelPlus\Extension\Repository\FileLoader;
use Illuminate\Filesystem\Filesystem;

class FileLoaderTests extends TestCase
{
    public function testEmptyArrayIsReturnedOnNullPath()
	{
		$loader = $this->getLoader();
		$this->assertEquals([], $loader->load('local', 'group', 'namespace'));
	}

	public function testBasicArrayIsReturned()
	{
		$loader = $this->getLoader();
		$loader->getFilesystem()->shouldReceive('exists')->once()->with(__DIR__.'/app.php')->andReturn(true);
		$loader->getFilesystem()->shouldReceive('getRequire')->once()->with(__DIR__.'/app.php')->andReturn(['foo' => 'bar']);
		$array = $loader->load('app', null);
		$this->assertEquals(['foo' => 'bar'], $array);
	}

	public function testGroupExistsReturnsTrueWhenTheGroupExists()
	{
		$loader = $this->getLoader();
		$loader->getFilesystem()->shouldReceive('exists')->once()->with(__DIR__.'/app.php')->andReturn(true);
		$this->assertTrue($loader->exists('app'));
	}

	public function testGroupExistsReturnsTrueWhenNamespaceGroupExists()
	{
		$loader = $this->getLoader();
		$loader->addNamespace('namespace', __DIR__.'/namespace');
		$loader->getFilesystem()->shouldReceive('exists')->once()->with(__DIR__.'/namespace/app.php')->andReturn(true);
		$this->assertTrue($loader->exists('app', 'namespace'));
	}

	public function testGroupExistsReturnsFalseWhenNamespaceHintDoesntExists()
	{
		$loader = $this->getLoader();
		$this->assertFalse($loader->exists('app', 'namespace'));
	}

	public function testGroupExistsReturnsFalseWhenNamespaceGroupDoesntExists()
	{
		$loader = $this->getLoader();
		$loader->addNamespace('namespace', __DIR__.'/namespace');
		$loader->getFilesystem()->shouldReceive('exists')->with(__DIR__.'/namespace/app.php')->andReturn(false);
		$this->assertFalse($loader->exists('app', 'namespace'));
	}

    protected function getLoader()
    {
        return new FileLoader($this->createMock(Filesystem::class), __DIR__);
    }
}
