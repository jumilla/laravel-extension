<?php

namespace LaravelPlus\Extension\Addons\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use LaravelPlus\Extension\Addons\MakeCommandTrait;
use LaravelPlus\Extension\Addons\AddonDirectory;
use LaravelPlus\Extension\Addons\AddonGenerator;
use UnexpectedValueException;
use Exception;

/**
 * Modules console commands.
 *
 * @author Fumio Furukawa <fumio.furukawa@gmail.com>
 */
class AddonMakeCommand extends Command
{
    use AddonCommandTrait;
    use MakeCommandTrait;

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'make:addon
        {name : The name of the addon.}
        {skeleton? : Skeleton of addon.}
        {--namespace= : PHP namespace of addon. Slash OK.}
        {--no-namespace : No PHP namespace.}
        {--language= : Languages, comma separated.}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[+] Create a new addon directory';

    /**
     * @var array
     */
    protected $skeletons = [
        1 => 'minimum',
        2 => 'simple',
        3 => 'library',
        4 => 'api',
        5 => 'ui',
        6 => 'debug',
        7 => 'generator',
        8 => 'laravel5',
        9 => 'sample:ui',
        10 => 'sample:auth',
    ];

    /**
     * @var string
     */
    protected $default_skeleton = 'sample:ui';

    /**
     * Execute the console command.
     *
     * @param \LaravelPlus\Extension\Addons\AddonGenerator $generator
     *
     * @return mixed
     */
    public function handle(Filesystem $filesystem, AddonGenerator $generator)
    {
        $addon_name = preg_replace('#(/+)#', '-', $this->argument('name'));

        $output_path = AddonDirectory::path($addon_name);

        if ($filesystem->exists($output_path)) {
            throw new UnexpectedValueException("addon directory '{$addon_name}' is already exists.");
        }

        $skeleton = $this->chooseSkeleton($this->argument('skeleton'));
        if ($this->option('no-namespace')) {
            $namespace = '';
        } else {
            if ($this->option('namespace')) {
                $namespace = str_replace('/', '\\', $this->option('namespace'));
                $namespace = studly_case(preg_replace('/[^\w_\\\\]/', '', $namespace));
            } else {
                $namespace = studly_case(preg_replace('/[^\w_]/', '', $addon_name));
            }

            $namespace = preg_replace('/^(\d)/', '_$1', $namespace);
        }
        $languages = $this->option('language') ? explode($this->option('language')) : [];

        $properties = [
            'addon_name' => preg_replace('/[^\w_]/', '', $addon_name),
            'addon_class' => preg_replace(
                ['/[^\w_]/', '/^(\d)/'],
                ['', '_$1'],
                studly_case($addon_name)
            ),
            'namespace' => $this->option('no-namespace') ? '' : $namespace,
            'languages' => array_unique(array_merge(['en', config('app.locale')], $languages)),
        ];

        try {
            $generator->generateAddon($output_path, str_replace(':', '-', $skeleton), $properties);
            $this->info('Addon Generated.');
        } catch (Exception $ex) {
            $filesystem->deleteDirectory($output_path);

            throw $ex;
        }
    }
}
