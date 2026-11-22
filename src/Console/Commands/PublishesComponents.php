<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Console\Commands;

use Bjnstnkvc\FormComponents\Helpers\Publisher;
use Bjnstnkvc\FormComponents\Helpers\Registrar;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class PublishesComponents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'components:publish { component?* } { --force : Overwrites existing components. }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy components into the app to change them freely.';

    /**
     * Execute the console command.
     *
     * @throws FileNotFoundException
     */
    public function handle(Filesystem $filesystem, Publisher $publisher, Registrar $registrar): int
    {
        if (! $filesystem->exists($publisher->configPath)) {
            $filesystem->ensureDirectoryExists(dirname($publisher->configPath));
            $filesystem->copy($publisher->getConfig(), $publisher->configPath);

            $this->info('Published the config to '.$this->path($publisher->configPath).'.');
        }

        $scripts = [];

        foreach ($this->components() as $view => $class) {
            $class = class_basename($class);

            if ($filesystem->exists($publisher->publishClass($class)) && ! $this->option('force')) {
                $this->warn('File '.$class.'.php already exists!');

                continue;
            }

            $filesystem->ensureDirectoryExists($publisher->classPath);
            $filesystem->ensureDirectoryExists($publisher->viewPath);
            $filesystem->put($publisher->publishClass($class), $publisher->updateClass($view, $filesystem->get($publisher->getClass($class))));
            $filesystem->copy($publisher->getView($view), $publisher->publishView($view));

            if ($filesystem->exists($publisher->getScript($view))) {
                $filesystem->ensureDirectoryExists($publisher->scriptPath);
                $filesystem->copy($publisher->getScript($view), $publisher->publishScript($view));

                $scripts[] = $view;
            }

            $filesystem->put($publisher->configPath, $publisher->updateConfig($class, $filesystem->get($publisher->configPath)));

            $this->info('Successfully published '.$class.' component class!');
        }

        $file = $registrar->useCopies($scripts);

        if ($file !== null) {
            $this->info('Registered the copied scripts with Alpine in '.$this->path($file).'.');
            $this->info('Run "npm run build" to bundle them.');
        }

        $this->callSilently('view:clear');

        return self::SUCCESS;
    }

    /**
     * Components to publish, keyed by view name.
     *
     * @return array<string, class-string>
     */
    private function components(): array
    {
        /** @var array<string, class-string> $components */
        $components = config('form_components.components') ?: [];

        /** @var list<string> $arguments */
        $arguments = $this->argument('component');

        return $arguments === [] ? $components : array_intersect_key($components, array_flip(array_map(
            fn (string $argument): string => Str::slug(Str::snake($argument)),
            $arguments,
        )));
    }

    /**
     * Path of a file relative to the app.
     */
    private function path(string $file): string
    {
        return Str::after($file, base_path().DIRECTORY_SEPARATOR);
    }
}
