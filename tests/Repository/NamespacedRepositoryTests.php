<?php

use LaravelPlus\Extension\Repository\NamespacedRepository;
use LaravelPlus\Extension\Repository\LoaderInterface;

class NamespacedRepositoryTests extends TestCase
{
    public function testHasGroupIndicatesIfConfigGroupExists()
	{
		$config = $this->getRepository();
		$config->getLoader()->shouldReceive('exists')->once()->with('group', 'namespace')->andReturn(false);
		$this->assertFalse($config->hasGroup('namespace::group'));
	}

	public function testHasOnTrueReturnsTrue()
	{
		$config = $this->getRepository();
		$options = $this->getDummyOptions();
		$config->getLoader()->shouldReceive('load')->once()->with('app', null)->andReturn($options);
		$this->assertTrue($config->has('app.bing'));
		$this->assertTrue($config->get('app.bing'));
	}

	public function testGetReturnsBasicItems()
	{
		$config = $this->getRepository();
		$options = $this->getDummyOptions();
		$config->getLoader()->shouldReceive('load')->once()->with('app', null)->andReturn($options);
		$this->assertEquals('bar', $config->get('app.foo'));
		$this->assertEquals('breeze', $config->get('app.baz.boom'));
		$this->assertEquals('blah', $config->get('app.code', 'blah'));
		$this->assertEquals('blah', $config->get('app.code', function() { return 'blah'; }));
	}

	public function testEntireArrayCanBeReturned()
	{
		$config = $this->getRepository();
		$options = $this->getDummyOptions();
		$config->getLoader()->shouldReceive('load')->once()->with('app', null)->andReturn($options);
		$this->assertEquals($options, $config->get('app'));
	}

	public function testLoaderGetsCalledCorrectForNamespaces()
	{
		$config = $this->getRepository();
		$options = $this->getDummyOptions();
		$config->getLoader()->shouldReceive('load')->once()->with('options', 'namespace')->andReturn($options);
		$this->assertEquals('bar', $config->get('namespace::options.foo'));
		$this->assertEquals('breeze', $config->get('namespace::options.baz.boom'));
		$this->assertEquals('blah', $config->get('namespace::options.code', 'blah'));
		$this->assertEquals('blah', $config->get('namespace::options.code', function() { return 'blah'; }));
	}

	public function testItemsCanBeSet()
	{
		$config = $this->getRepository();
		$config->getLoader()->shouldReceive('load')->once()->with('foo', null)->andReturn(['name' => 'dayle']);
		$config->set('foo.name', 'taylor');
		$this->assertEquals('taylor', $config->get('foo.name'));
		$config = $this->getRepository();
		$config->getLoader()->shouldReceive('load')->once()->with('foo', 'namespace')->andReturn(['name' => 'dayle']);
		$config->set('namespace::foo.name', 'taylor');
		$this->assertEquals('taylor', $config->get('namespace::foo.name'));
	}

	protected function getRepository()
	{
        return new NamespacedRepository($this->createMock(LoaderInterface::class), __DIR__);
	}

	protected function getDummyOptions()
	{
		return ['foo' => 'bar', 'baz' => ['boom' => 'breeze'], 'bing' => true];
	}
}
