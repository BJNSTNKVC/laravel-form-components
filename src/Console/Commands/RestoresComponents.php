<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Console\Commands;

use Bjnstnkvc\FormComponents\Helpers\Publisher;
use Bjnstnkvc\FormComponents\Helpers\Registrar;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class RestoresComponents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'components:restore { component?* } { --delete : Deletes Form component files. }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Switch copied components back to the package ones.';

    /**
     * Execute the console command.
     *
     * @throws FileNotFoundException
     */
    public function handle(Filesystem $filesystem, Publisher $publisher, Registrar $registrar): int
    {
        $scripts = [];

        foreach ($this->components($publisher) as $view => $class) {
            $class = class_basename($class);

            if ($filesystem->exists($publisher->configPath)) {
                $filesystem->put($publisher->configPath, $publisher->restoreClass($class, $filesystem->get($publisher->configPath)));
            }

            if ($this->option('delete')) {
                $filesystem->delete([
                    $publisher->publishClass($class),
                    $publisher->publishView($view),
                    $publisher->publishScript($view),
                ]);
            }

            if ($filesystem->exists($publisher->getScript($view))) {
                $scripts[] = $view;
            }

            $this->info('Successfully restored '.$class.' component class!');
        }

        $file = $registrar->usePackage($scripts);

        if ($file !== null) {
            $this->info('Registered the package scripts with Alpine in '.Str::after($file, base_path().DIRECTORY_SEPARATOR).'.');
            $this->info('Run "npm run build" to bundle them.');
        }

        $this->callSilently('view:clear');

        return self::SUCCESS;
    }

    /**
     * Components to restore, keyed by view name.
     *
     * @return array<string, class-string>
     */
    private function components(Publisher $publisher): array
    {
        /** @var array<string, class-string> $components */
        $components = config('form_components.components') ?: [];

        /** @var list<string> $arguments */
        $arguments = $this->argument('component');

        $names = $arguments === []
            ? array_map(fn (string $file): string => basename($file, '.php'), glob($publisher->classPath.'*.php') ?: [])
            : $arguments;

        return array_intersect_key($components, array_flip(array_map(fn (string $name): string => Str::slug(Str::snake($name)), $names)));
    }
}
