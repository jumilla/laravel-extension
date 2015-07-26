<?php

use Illuminate\Database\DatabaseManager;

abstract class TestCase extends PHPUnit_Framework_TestCase
{
    use MockeryTrait;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Contracts\Foundation\Application
     */
    protected function createApplication()
    {
        $GLOBALS['app'] = $this->app = new ApplicationStub([
            'db' => DatabaseManager::class,
        ]);

        return $this->app;
    }
}
