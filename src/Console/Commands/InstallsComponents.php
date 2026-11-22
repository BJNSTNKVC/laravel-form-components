<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Console\Commands;

use Bjnstnkvc\FormComponents\Helpers\Publisher;
use Bjnstnkvc\FormComponents\Helpers\Registrar;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class InstallsComponents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'form-components:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register the form components with Alpine.';

    /**
     * Alpine version added to apps that lack it.
     */
    private const string ALPINE = '^3.15.0';

    /**
     * Filesystem the command reads and writes through.
     */
    private Filesystem $filesystem;

    /**
     * Publisher locating the copied components.
     */
    private Publisher $publisher;

    /**
     * Execute the console command.
     *
     * @throws FileNotFoundException
     */
    public function handle(Filesystem $filesystem, Publisher $publisher, Registrar $registrar): int
    {
        $this->filesystem = $filesystem;
        $this->publisher = $publisher;

        $file = $registrar->entry();
        $content = $filesystem->exists($file) ? $filesystem->get($file) : '';

        $names = array_values(array_filter(
            $this->names(),
            fn (string $name): bool => ! $registrar->registers($content, $name),
        ));

        if ($names === []) {
            $this->info('The form components are already registered with Alpine.');

            return self::SUCCESS;
        }

        $scripts = [];
        $imports = [];

        foreach ($names as $name) {
            [$scripts[$name], $needed] = $this->isCopied($name)
                ? $registrar->copied($file, $name)
                : $registrar->package($content, $file, $name);

            $imports = [...$imports, ...$needed];
        }

        $added = false;

        if (preg_match(Registrar::START, $content, $start, PREG_OFFSET_CAPTURE) === 1) {
            $content = substr_replace(
                $content,
                $this->registrations($registrar, $scripts, str_replace('Livewire', 'Alpine', $start[2][0]), $start[1][0])."\n\n",
                $start[0][1],
                0,
            );
        } elseif ($this->usesLivewire()) {
            $content = $this->append($content, implode("\n", [
                "document.addEventListener('alpine:init', () => {",
                $this->registrations($registrar, $scripts, 'window.Alpine', '    '),
                '});',
            ]));
        } else {
            $content = $this->append($content, implode("\n\n", [
                'window.Alpine = Alpine;',
                $this->registrations($registrar, $scripts, 'Alpine'),
                'Alpine.start();',
            ]));

            if (preg_match('/^import\s+Alpine\b/m', $content) !== 1) {
                array_unshift($imports, "import Alpine from 'alpinejs';");
            }

            $added = $this->requireAlpine();
        }

        $filesystem->ensureDirectoryExists(dirname($file));
        $filesystem->put($file, $registrar->import($content, $imports));

        $this->info('Registered the form components with Alpine in '.Str::after($file, base_path().DIRECTORY_SEPARATOR).'.');

        if ($added) {
            $this->info('Added Alpine to package.json.');
        }

        $this->info('Run "'.($added ? 'npm install && ' : '').'npm run build" to bundle them.');

        return self::SUCCESS;
    }

    /**
     * Configured components that ship a script.
     *
     * @return list<string>
     */
    private function names(): array
    {
        $names = [];

        foreach (glob(dirname(__DIR__, 2).'/Components/*/resources/js/*.js') ?: [] as $script) {
            $name = basename($script, '.js');

            if (array_key_exists($name, $this->components())) {
                $names[] = $name;
            }
        }

        return $names;
    }

    /**
     * Determine whether the app uses its copy of a component.
     */
    private function isCopied(string $name): bool
    {
        $class = $this->components()[$name];

        return $class === $this->publisher->publishedPath.class_basename($class)
            && $this->filesystem->exists($this->publisher->publishScript($name));
    }

    /**
     * Components the app configures, keyed by name.
     *
     * @return array<string, class-string>
     */
    private function components(): array
    {
        /** @var array<string, class-string> $components */
        $components = config('form_components.components') ?: [];

        return $components;
    }

    /**
     * Lines registering the components with Alpine.
     *
     * @param  array<string, string>  $scripts
     */
    private function registrations(Registrar $registrar, array $scripts, string $alpine, string $indent = ''): string
    {
        $lines = [];

        foreach ($scripts as $name => $script) {
            $lines[] = $indent.$alpine.".data('".$registrar->data($name)."', ".$script.');';
        }

        return implode("\n", $lines);
    }

    /**
     * Add a block to the end of a script.
     */
    private function append(string $content, string $block): string
    {
        $content = rtrim($content);

        return ($content === '' ? '' : $content."\n\n").$block."\n";
    }

    /**
     * Determine whether the app requires Livewire, which brings Alpine.
     *
     * @throws FileNotFoundException
     */
    private function usesLivewire(): bool
    {
        $composer = $this->json(base_path('composer.json'));

        return is_array($composer['require'] ?? null) && array_key_exists('livewire/livewire', $composer['require']);
    }

    /**
     * Add Alpine to package.json unless the app already requires it.
     *
     * @throws FileNotFoundException
     */
    private function requireAlpine(): bool
    {
        $path = base_path('package.json');
        $package = $this->json($path);

        foreach (['dependencies', 'devDependencies'] as $key) {
            if (is_array($package[$key] ?? null) && array_key_exists('alpinejs', $package[$key])) {
                return false;
            }
        }

        $dependencies = is_array($package['devDependencies'] ?? null) ? $package['devDependencies'] : [];
        $dependencies['alpinejs'] = self::ALPINE;
        ksort($dependencies);

        $package['devDependencies'] = $dependencies;

        $this->filesystem->put($path, json_encode($package, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)."\n");

        return true;
    }

    /**
     * Decoded JSON file, empty when missing or invalid.
     *
     * @return array<mixed>
     *
     * @throws FileNotFoundException
     */
    private function json(string $path): array
    {
        $json = $this->filesystem->exists($path) ? json_decode($this->filesystem->get($path), true) : null;

        return is_array($json) ? $json : [];
    }
}
