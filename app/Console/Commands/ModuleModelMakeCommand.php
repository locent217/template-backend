<?php

namespace App\Console\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ModuleModelMakeCommand extends ModelMakeCommand
{
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->setName('make:module-model');
        $this->getDefinition()->addOption(
            new InputOption(
                'module',
                null,
                InputOption::VALUE_NONE,
                'Generate service and repository classes'
            )
        );
    }

    public function handle()
    {
        $result = parent::handle();

        if ($result === false || $result === 1) {
            return $result;
        }

        if (! $this->option('module')) {
            return $result;
        }

        $model = Str::studly(class_basename(
            str_replace('\\', '/', $this->argument('name'))
        ));

        $this->generateModule($model);

        return $result;
    }

    protected function generateModule(string $model): void
    {
        $root = app_path("Modules/{$model}");

        $files = [
            "{$root}/Repository/{$model}RepositoryInterface.php"
            => 'repository-interface.stub',

            "{$root}/Repository/Eloquent/{$model}Repository.php"
            => 'repository.stub',

            "{$root}/Service/{$model}Service.php"
            => 'service.stub',

            app_path(
                "Providers/RepositoryServiceProviders/{$model}RepositoryServiceProvider.php"
            ) => 'repository-provider.stub',

            app_path("Http/Controllers/{$model}Controller.php")
            => 'controller.stub',

            app_path("Http/Requests/{$model}/Add{$model}Request.php")
            => 'add-request.stub',

            app_path("Http/Requests/{$model}/Update{$model}Request.php")
            => 'update-request.stub',

            base_path("routes/modules/{$model}Routes.php")
            => 'route.stub',
        ];

        foreach ($files as $path => $stub) {
            if (File::exists($path)) {
                $this->components->warn("Already exists: {$path}");
                continue;
            }

            File::ensureDirectoryExists(dirname($path));

            $content = File::get(
                base_path("stubs/modules/{$stub}")
            );

            $content = str_replace(
                ['{{ model }}', '{{ route }}', '{{ permission }}'],
                [$model, Str::kebab(Str::pluralStudly($model)), Str::snake(Str::pluralStudly($model))],
                $content
            );

            File::put($path, $content);

            $this->components->info("Created: {$path}");
        }
    }
}
