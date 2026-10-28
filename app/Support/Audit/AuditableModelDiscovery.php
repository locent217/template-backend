<?php

namespace App\Support\Audit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use ReflectionClass;

final class AuditableModelDiscovery
{
    /** @return list<class-string<Model>> */
    public static function discover(): array
    {
        $models = [];
        $excluded = config('audit.excluded_models', []);

        foreach (config('audit.model_directories', ['Models', 'Modules']) as $directory) {
            $base = app_path($directory);
            if (!is_dir($base)) {
                continue;
            }

            foreach (File::allFiles($base) as $file) {
                if ($file->getExtension() !== 'php') {
                    continue;
                }

                $relative = substr($file->getPathname(), strlen(app_path()) + 1);
                $class = 'App\\' . str_replace('/', '\\', substr($relative, 0, -4));
                if (!class_exists($class)) {
                    continue;
                }

                $reflection = new ReflectionClass($class);
                if (!$reflection->isInstantiable()
                    || !$reflection->isSubclassOf(Model::class)
                    || in_array($class, $excluded, true)) {
                    continue;
                }

                $models[$class] = $class;
            }
        }

        return array_values($models);
    }
}
