<?php

namespace LaravelPlus\Extension\Addons;

use LaravelPlus\Extension\Generators\ClassName;
use LaravelPlus\Extension\Generators\FileGenerator;

class AddonGenerator
{
    /**
     * @param string $path
     * @param string $type
     * @param array  $properties
     */
    public function generateAddon($path, $type, array $properties)
    {
        $generator = FileGenerator::make($path, __DIR__.'/stubs/'.$type);

        $method = 'generate'.studly_case($type);

        call_user_func([$this, $method], $generator, $properties);
    }

    protected function generateMinimum(FileGenerator $generator, array $properties)
    {
        $generator->directory('classes', function ($generator) use ($properties) {
            $generator->directory('Providers')
                ->file('AddonServiceProvider.php')->template('AddonServiceProvider.php', $properties);
        });

        $this->generateAddonConfig($generator, [
            'namespace' => $properties['namespace'],
            'directories' => [
                'classes',
            ],
            'paths' => [
            ],
            'providers' => [
                new ClassName($properties['namespace'].'\Providers\AddonServiceProvider'),
            ],
        ]);
    }

    protected function generateSimple(FileGenerator $generator, array $properties)
    {
        $generator->directory('classes', function ($generator) use ($properties) {
            $generator->directory('Providers')
                ->file('AddonServiceProvider.php')->template('AddonServiceProvider.php', $properties);
            $generator->directory('Providers')
                ->file('RouteServiceProvider.php')->template('RouteServiceProvider.php', $properties);

            $generator->directory('Http')->phpBlankFile('routes.php');
            $generator->keepDirectory('Http/Controllers');

            $generator->keepDirectory('Services');
        });

        $generator->keepDirectory('config');

        $generator->directory('resources', function ($generator) use ($properties) {
            $this->generateLang($generator, $properties, function ($generator) use ($properties) {
                $generator->gitKeepFile();
            });

            $generator->keepDirectory('views');
        });

        $generator->phpBlankFile('helpers.php');

        $this->generateAddonConfig($generator, [
            'namespace' => $properties['namespace'],
            'directories' => [
                'classes',
            ],
            'files' => [
                'helpers.php',
            ],
            'paths' => [
                'config' => 'config',
                'lang' => 'resources/lang',
                'views' => 'resources/views',
            ],
            'providers' => [
                new ClassName($properties['namespace'].'\Providers\AddonServiceProvider'),
                new ClassName($properties['namespace'].'\Providers\RouteServiceProvider'),
            ],
        ]);
    }

    protected function generateLibrary(FileGenerator $generator, array $properties)
    {
        $generator->directory('classes', function ($generator) use ($properties) {
            $migration_class = $properties['addon_class'].'_1_0';

            $generator->directory('Providers')
                ->file('AddonServiceProvider.php')->template('AddonServiceProvider.php', $properties);
            $generator->directory('Providers')
                ->file('DatabaseServiceProvider.php')->template('DatabaseServiceProvider.php', array_merge($properties, ['migration_class_name' => $migration_class]));

            $generator->keepDirectory('Console/Commands');

            $generator->directory('Database/Migrations')
                ->file($migration_class.'.php')->template('Migration.php', array_merge($properties, ['class_name' => $migration_class]));
            $generator->keepDirectory('Database/Seeds');

            $generator->keepDirectory('Services');
        });

        $generator->keepDirectory('config');

        $generator->directory('resources', function ($generator) use ($properties) {
            $this->generateLang($generator, $properties, function ($generator) use ($properties) {
                $generator->phpConfigFile('messages.php', []);
            });
        });

        $generator->directory('tests', function ($generator) use ($properties) {
            $generator->file('TestCase.php')->template('TestCase.php', $properties);
        });

        $generator->phpBlankFile('helpers.php');

        $this->generateAddonConfig($generator, [
            'namespace' => $properties['namespace'],
            'directories' => [
                'classes',
            ],
            'files' => [
                'helpers.php',
            ],
            'paths' => [
                'config' => 'config',
                'lang' => 'resources/lang',
            ],
            'providers' => [
                new ClassName($properties['namespace'].'\Providers\AddonServiceProvider'),
                new ClassName($properties['namespace'].'\Providers\DatabaseServiceProvider'),
            ],
        ]);
    }

    protected function generateApi(FileGenerator $generator, array $properties)
    {
        $generator->directory('classes', function ($generator) use ($properties) {
            $generator->directory('Providers')
                ->file('AddonServiceProvider.php')->template('AddonServiceProvider.php', $properties);
            $generator->directory('Providers')
                ->file('RouteServiceProvider.php')->template('RouteServiceProvider.php', $properties);

            $generator->keepDirectory('Console/Commands');

            $generator->directory('Http')
                ->file('routes.php')->template('routes.php', $properties);
            $generator->directory('Http/Controllers')
                ->file('Controller.php')->template('Controller.php', $properties);
            $generator->keepDirectory('Http/Middleware');

            $generator->keepDirectory('Services');
        });

        $generator->keepDirectory('config');

        $generator->directory('resources', function ($generator) use ($properties) {
            $this->generateLang($generator, $properties, function ($generator) use ($properties) {
                $generator->phpConfigFile('messages.php', []);
                $generator->phpConfigFile('vocabulary.php', []);
                $generator->phpConfigFile('methods.php', []);
            });

            $generator->directory('specs')->phpConfigFile('methods.php', []);
        });

        $generator->directory('tests', function ($generator) use ($properties) {
            $generator->file('TestCase.php')->template('TestCase.php', $properties);
        });

        $generator->phpBlankFile('helpers.php');

        $this->generateAddonConfig($generator, [
            'namespace' => $properties['namespace'],
            'directories' => [
                'classes',
            ],
            'files' => [
                'helpers.php',
            ],
            'paths' => [
                'config' => 'config',
                'lang' => 'resources/lang',
                'specs' => 'resources/specs',
            ],
            'providers' => [
                new ClassName($properties['namespace'].'\Providers\AddonServiceProvider'),
                new ClassName($properties['namespace'].'\Providers\RouteServiceProvider'),
            ],
        ]);
    }

    protected function generateUi(FileGenerator $generator, array $properties)
    {
        $generator->directory('classes', function ($generator) use ($properties) {
            $migration_class = $properties['addon_class'].'_1_0';

            $generator->directory('Providers')
                ->file('AddonServiceProvider.php')->template('AddonServiceProvider.php', $properties);
            $generator->directory('Providers')
                ->file('DatabaseServiceProvider.php')->template('DatabaseServiceProvider.php', array_merge($properties, ['migration_class_name' => $migration_class]));
            $generator->directory('Providers')
                ->file('RouteServiceProvider.php')->template('RouteServiceProvider.php', $properties);

            $generator->keepDirectory('Console/Commands');

            $generator->directory('Database/Migrations')
                ->file($migration_class.'.php')->template('Migration.php', array_merge($properties, ['class_name' => $migration_class]));
            $generator->keepDirectory('Database/Seeds');

            $generator->directory('Http')
                ->file('routes.php')->template('routes.php', $properties);
            $generator->directory('Http/Controllers')
                ->file('Controller.php')->template('Controller.php', $properties);
            $generator->keepDirectory('Http/Middleware');

            $generator->keepDirectory('Services');
        });

        $generator->keepDirectory('config');

        $generator->directory('resources', function ($generator) use ($properties) {
            $generator->keepDirectory('assets');

            $this->generateLang($generator, $properties, function ($generator) use ($properties) {
                $generator->phpConfigFile('messages.php', []);
                $generator->phpConfigFile('vocabulary.php', []);
                $generator->phpConfigFile('forms.php', []);
            });

            $generator->directory('specs')->phpConfigFile('forms.php', []);

            $generator->directory('views')
                ->file('index.blade.php')->template('index.blade.php', $properties);
            $generator->directory('views')
                ->file('layout.blade.php')->template('layout.blade.php', $properties);
        });

        $generator->directory('tests', function ($generator) use ($properties) {
            $generator->file('TestCase.php')->template('TestCase.php', $properties);
        });

        $generator->phpBlankFile('helpers.php');

        $this->generateAddonConfig($generator, [
            'namespace' => $properties['namespace'],
            'directories' => [
                'classes',
            ],
            'files' => [
                'helpers.php',
            ],
            'paths' => [
                'config' => 'config',
                'assets' => 'resources/assets',
                'lang' => 'resources/lang',
                'specs' => 'resources/specs',
                'views' => 'resources/views',
            ],
            'providers' => [
                new ClassName($properties['namespace'].'\Providers\AddonServiceProvider'),
                new ClassName($properties['namespace'].'\Providers\DatabaseServiceProvider'),
                new ClassName($properties['namespace'].'\Providers\RouteServiceProvider'),
            ],
        ]);
    }

    protected function generateDebug(FileGenerator $generator, array $properties)
    {
        $generator->directory('classes', function ($generator) use ($properties) {
            $generator->directory('Providers')
                ->file('AddonServiceProvider.php')->template('AddonServiceProvider.php', $properties);
            $generator->directory('Providers')
                ->file('RouteServiceProvider.php')->template('RouteServiceProvider.php', $properties);

            $generator->keepDirectory('Console/Commands');

            $generator->directory('Http')
                ->file('routes.php')->template('routes.php', $properties);
            $generator->directory('Http/Controllers')
                ->file('Controller.php')->template('Controller.php', $properties);
            $generator->directory('Http/Controllers')
                ->file('DebugController.php')->template('DebugController.php', $properties);
            $generator->keepDirectory('Http/Middleware');

            $generator->keepDirectory('Services');
        });

        $generator->keepDirectory('config');

        $generator->directory('resources', function ($generator) use ($properties) {
            $this->generateLang($generator, $properties, function ($generator) use ($properties) {
                $generator->phpConfigFile('messages.php', []);
                $generator->phpConfigFile('vocabulary.php', []);
                $generator->phpConfigFile('forms.php', []);
                $generator->phpConfigFile('methods.php', []);
            });

            $generator->directory('specs')->phpConfigFile('forms.php', []);
            $generator->directory('specs')->phpConfigFile('methods.php', []);

            $generator->directory('views')
                ->file('index.blade.php')->template('index.blade.php', $properties);
            $generator->directory('views')
                ->file('layout.blade.php')->template('layout.blade.php', $properties);
        });

        $generator->phpBlankFile('helpers.php');

        $this->generateAddonConfig($generator, [
            'namespace' => $properties['namespace'],
            'directories' => [
                'classes',
            ],
            'files' => [
                'helpers.php',
            ],
            'paths' => [
                'config' => 'config',
                'lang' => 'resources/lang',
                'specs' => 'resources/specs',
                'views' => 'resources/views',
            ],
            'providers' => [
                new ClassName($properties['namespace'].'\Providers\AddonServiceProvider'),
                new ClassName($properties['namespace'].'\Providers\RouteServiceProvider'),
            ],
        ]);
    }

    protected function generateLaravel5(FileGenerator $generator, array $properties)
    {
        $generator->directory('app', function ($generator) use ($properties) {
            $generator->directory('Providers')
                ->file('AddonServiceProvider.php')->template('AddonServiceProvider.php', $properties);
            $generator->directory('Providers')
                ->file('RouteServiceProvider.php')->template('RouteServiceProvider.php', $properties);

            $generator->keepDirectory('Console/Commands');

            $generator->directory('Http')
                ->file('routes.php')->template('routes.php', $properties);
            $generator->directory('Http/Controllers')
                ->file('Controller.php')->template('Controller.php', $properties);
            $generator->keepDirectory('Http/Middleware');
            $generator->directory('Http/Requests')
                ->file('Request.php')->template('Request.php', $properties);

            $generator->keepDirectory('Services');
        });

        $generator->keepDirectory('config');

        $generator->directory('database', function ($generator) use ($properties) {
            $generator->keepDirectory('factories');
            $generator->keepDirectory('migrations');
            $generator->keepDirectory('seeds');
        });

        $generator->directory('resources', function ($generator) use ($properties) {
            $generator->keepDirectory('assets');

            $this->generateLang($generator, $properties, function ($generator) use ($properties) {
                $generator->phpConfigFile('messages.php', []);
            });

            $generator->keepDirectory('views');
        });

        $generator->directory('tests', function ($generator) use ($properties) {
            $generator->file('TestCase.php')->template('TestCase.php', $properties);
        });

        $generator->phpBlankFile('helpers.php');

        $this->generateAddonConfig($generator, [
            'namespace' => $properties['namespace'],
            'directories' => [
                'app',
            ],
            'files' => [
                'helpers.php',
            ],
            'paths' => [
                'config' => 'config',
                'assets' => 'resources/assets',
                'lang' => 'resources/lang',
                'views' => 'resources/views',
            ],
            'providers' => [
                new ClassName($properties['namespace'].'\Providers\AddonServiceProvider'),
                new ClassName($properties['namespace'].'\Providers\RouteServiceProvider'),
            ],
        ]);
    }

    protected function generateSampleUi(FileGenerator $generator, array $properties)
    {
        $generator->directory('classes', function ($generator) use ($properties) {
            $migration_class = $properties['addon_class'].'_1_0';

            $generator->directory('Providers')
                ->file('AddonServiceProvider.php')->template('AddonServiceProvider.php', $properties);
            $generator->directory('Providers')
                ->file('RouteServiceProvider.php')->template('RouteServiceProvider.php', $properties);
            $generator->directory('Providers')
                ->file('DatabaseServiceProvider.php')->template('DatabaseServiceProvider.php', array_merge($properties, ['migration_class_name' => $migration_class]));

            $generator->keepDirectory('Console/Commands');

            $generator->directory('Database/Migrations')
                ->file($migration_class.'.php')->template('Migration.php', array_merge($properties, ['class_name' => $migration_class]));
            $generator->keepDirectory('Database/Seeds');

            $generator->directory('Http')
                ->file('routes.php')->template('routes.php', $properties);
            $generator->directory('Http/Controllers')
                ->file('Controller.php')->template('Controller.php', $properties);
            $generator->directory('Http/Controllers')
                ->file('SampleController.php')->template('SampleController.php', $properties);
            $generator->keepDirectory('Http/Middleware');

            $generator->keepDirectory('Services');
        });

        $generator->keepDirectory('config');

        $generator->directory('resources', function ($generator) use ($properties) {
            $generator->keepDirectory('assets');

            $this->generateLang($generator, $properties, function ($generator) use ($properties) {
                $generator->phpConfigFile('messages.php', []);
                $generator->phpConfigFile('vocabulary.php', []);
                $generator->phpConfigFile('forms.php', []);
            });
            $generator->directory('lang/en')->file('messages.php')->template('lang-en-messages.php', $properties);
            if (in_array('ja', $properties['languages'])) {
                $generator->directory('lang/ja')->file('messages.php')->template('lang-ja-messages.php', $properties);
            }

            $generator->directory('specs')->phpConfigFile('forms.php', []);

            $generator->directory('views')
                ->file('index.blade.php')->template('index.blade.php', $properties);
            $generator->directory('views')
                ->file('layout.blade.php')->template('layout.blade.php', $properties);
        });

        $generator->directory('tests', function ($generator) use ($properties) {
            $generator->file('TestCase.php')->template('TestCase.php', $properties);
        });

        $generator->phpBlankFile('helpers.php');

        $this->generateAddonConfig($generator, [
            'namespace' => $properties['namespace'],
            'directories' => [
                'classes',
            ],
            'files' => [
                'helpers.php',
            ],
            'paths' => [
                'config' => 'config',
                'assets' => 'resources/assets',
                'lang' => 'resources/lang',
                'specs' => 'resources/specs',
                'views' => 'resources/views',
            ],
            'providers' => [
                new ClassName($properties['namespace'].'\Providers\AddonServiceProvider'),
                new ClassName($properties['namespace'].'\Providers\DatabaseServiceProvider'),
                new ClassName($properties['namespace'].'\Providers\RouteServiceProvider'),
            ],
        ]);
    }

    protected function generateSampleAuth(FileGenerator $generator, array $properties)
    {
        $generator->directory('classes', function ($generator) use ($properties) {
            $generator->keepDirectory('Console/Commands');

            $generator->templateDirectory('Database/Migrations', $properties);
            $generator->keepDirectory('Database/Seeds');

            $generator->templateDirectory('Http', $properties);

            $generator->templateDirectory('Providers', $properties);

            $generator->keepDirectory('Services');

            $generator->templateFile('User.php', $properties);
        });

        $generator->keepDirectory('config');

        $generator->directory('resources', function ($generator) use ($properties) {
            $generator->keepDirectory('assets');

            $generator->sourceDirectory('lang');
            $this->generateLang($generator, $properties, function ($generator) use ($properties) {
                $generator->phpConfigFile('messages.php', []);
                $generator->phpConfigFile('vocabulary.php', []);
                $generator->phpConfigFile('forms.php', []);
            });

            $generator->directory('specs')->phpConfigFile('forms.php', []);

            $generator->sourceDirectory('views');
        });

        $generator->sourceDirectory('public');

        $generator->templateDirectory('tests', $properties);

        $generator->phpBlankFile('helpers.php');

        $this->generateAddonConfig($generator, [
            'namespace' => $properties['namespace'],
            'directories' => [
                'classes',
            ],
            'files' => [
                'helpers.php',
            ],
            'paths' => [
                'config' => 'config',
                'assets' => 'resources/assets',
                'lang' => 'resources/lang',
                'specs' => 'resources/specs',
                'views' => 'resources/views',
            ],
            'http' => [
                'middlewares' => [
                ],
                'route_middlewares' => [
                    'auth' => new ClassName($properties['namespace'].'\Http\Middleware\Authenticate'),
                    'auth.basic' => new ClassName('Illuminate\Auth\Middleware\AuthenticateWithBasicAuth'),
                    'guest' => new ClassName($properties['namespace'].'\Http\Middleware\RedirectIfAuthenticated'),
                ],
            ],
            'providers' => [
                new ClassName($properties['namespace'].'\Providers\AddonServiceProvider'),
                new ClassName($properties['namespace'].'\Providers\DatabaseServiceProvider'),
                new ClassName($properties['namespace'].'\Providers\RouteServiceProvider'),
            ],
        ]);
    }

    protected function generateLang(FileGenerator $generator, array $properties, callable $callable)
    {
        $generator->directory('lang', function ($generator) use ($properties, $callable) {
            foreach ($properties['languages'] as $lang) {
                $generator->directory($lang, $callable);
            }
        });
    }

    protected function generateAddonConfig(FileGenerator $generator, array $data)
    {
        $defaults = [
            'version' => 5,
            'namespace' => '',
            'directories' => [
                // path
            ],
            'files' => [
                // path
            ],
            'paths' => [
                // role => path
            ],
            'providers' => [
                // class
            ],
            'console' => [
                'commands' => [
                    // class
                ],
            ],
            'http' => [
                'middlewares' => [
                    // class
                ],
                'route_middlewares' => [
                    // name => class
                ],
            ],
            'includes_global_aliases' => true,
            'aliases' => [
                // name => class
            ],
        ];

        $data = array_replace($defaults, $data);

        $generator->phpConfigFile('addon.php', $data);
    }
}
