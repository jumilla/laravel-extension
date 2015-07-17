<?php

namespace LaravelPlus\Extension\Console;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Carbon\Carbon;

class TailCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'tail
        {--path= : The fully qualified path to the log file.}
        {--lines=20 : The number of lines to tail.}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[+] Tail a log file';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // syslog not support
        if ($this->laravel['config']['app.log'] === 'syslog') {
            $this->error('syslog not support.');

            return;
        }

        $connection = $this->argument('connection');

        // on Local
        if (is_null($connection)) {
            $path = $this->option('path') ?: $this->getLocalPath();

            if ($path) {
                $this->tailLocalLogs($path);
            } else {
                $this->error('Could not determine path to log file.');
            }
        }
        // on Remote
        else {
            $path = $this->option('path') ?: $this->getRemotePath($connection);

            if ($path) {
                $this->tailRemoteLogs($path, $connection);
            } else {
                $this->error('Could not determine path to log file.');
            }
        }
    }

    /**
     * Get the path to the Laravel log file.
     *
     * @return string
     */
    protected function getLocalPath()
    {
        switch ($this->laravel['config']['app.log']) {
        case 'single':
            return storage_path('logs/laravel.log');

        case 'daily':
            $date = Carbon::today()->format('Y-m-d');

            return storage_path("logs/laravel-{$date}.log");

        case 'syslog':
            throw new \RuntimeException('syslog not support');
        }
    }

    /**
     * Tail a local log file for the application.
     *
     * @param  string  $path
     * @return string
     */
    protected function tailLocalLogs($path)
    {
        $output = $this->output;

        $lines = $this->option('lines');

        (new Process('tail -f -n '.$lines.' '.escapeshellarg($path)))->setTimeout(null)->run(function ($type, $line) use ($output) {
            $output->write($line);
        });
    }

    /**
     * Get the path to the Laravel log file.
     *
     * @param  string  $connection
     * @return string
     */
    protected function getRemotePath($connection)
    {
        return $this->getRoot($connection).str_replace(base_path(), '', $this->getRemotePathFromStorage());
    }

    /**
     * Get the path to the Laravel log file.
     *
     * @return string
     */
    protected function getRemotePathFromStorage()
    {
        switch ($this->laravel['config']['app.log']) {
        case 'single':
            return storage_path('logs/laravel.log');

        case 'daily':
            $date = Carbon::today()->format('Y-m-d');

            return storage_path("logs/laravel-{$date}.log");

        case 'syslog':
            throw new \RuntimeException('syslog not support');
        }
    }

    /**
     * Get the path to the Laravel install root.
     *
     * @param  string  $connection
     * @return string
     */
    protected function getRoot($connection)
    {
        return $this->laravel['config']['remote.connections.'.$connection.'.root'];
    }

    /**
     * Tail a remote log file at the given path and connection.
     *
     * @param  string  $path
     * @param  string  $connection
     * @return void
     */
    protected function tailRemoteLogs($path, $connection)
    {
        $out = $this->output;

        $lines = $this->option('lines');

        $this->getRemote($connection)->run('tail -f -n '.$lines.' '.escapeshellarg($path), function ($line) use ($out) {
            $out->write($line);
        });
    }

    /**
     * Get a connection to the remote server.
     *
     * @param  string  $connection
     * @return \Illuminate\Remote\Connection
     */
    protected function getRemote($connection)
    {
        return $this->laravel['remote']->connection($connection);
    }
}
