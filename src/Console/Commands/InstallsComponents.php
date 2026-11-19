<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Console\Commands;

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
     * Line where Alpine, or Livewire bringing it, starts.
     */
    private const string START = '/^([ \t]*)((?:window\.)?(?:Alpine|Livewire))\.start\(\s*\)/m';

    /**
     * Name the app imports the component scripts under.
     */
    private const string NAMESPACE = 'form';

    /**
     * Filesystem the command reads and writes through.
     */
    private Filesystem $filesystem;

    /**
     * Execute the console command.
     *
     * @throws FileNotFoundException
     */
    public function handle(Filesystem $filesystem): int
    {
        $this->filesystem = $filesystem;

        $file = $this->entry();
        $content = $filesystem->exists($file) ? $filesystem->get($file) : '';

        $names = array_values(array_filter(
            $this->names(),
            fn (string $name): bool => preg_match('/Alpine\.data\(\s*[\'"]'.$this->data($name).'[\'"]/', $content) !== 1,
        ));

        if ($names === []) {
            $this->info('The form components are already registered with Alpine.');

            return self::SUCCESS;
        }

        $index = $this->relative(dirname($file), dirname(__DIR__, 2).'/resources/js/index.js');
        $imported = preg_match('/^import\s+\*\s+as\s+(\w+)\s+from\s+[\'"]'.preg_quote($index, '/').'[\'"]/m', $content, $import) === 1;
        $namespace = $imported ? $import[1] : self::NAMESPACE;
        $imports = $imported ? [] : ['import * as '.$namespace." from '".$index."';"];
        $added = false;

        if (preg_match(self::START, $content, $start, PREG_OFFSET_CAPTURE) === 1) {
            $content = substr_replace(
                $content,
                $this->registrations($names, $namespace, str_replace('Livewire', 'Alpine', $start[2][0]), $start[1][0])."\n\n",
                $start[0][1],
                0,
            );
        } elseif ($this->usesLivewire()) {
            $content = $this->append($content, implode("\n", [
                "document.addEventListener('alpine:init', () => {",
                $this->registrations($names, $namespace, 'window.Alpine', '    '),
                '});',
            ]));
        } else {
            $content = $this->append($content, implode("\n\n", [
                'window.Alpine = Alpine;',
                $this->registrations($names, $namespace, 'Alpine'),
                'Alpine.start();',
            ]));

            if (preg_match('/^import\s+Alpine\b/m', $content) !== 1) {
                array_unshift($imports, "import Alpine from 'alpinejs';");
            }

            $added = $this->requireAlpine();
        }

        $filesystem->ensureDirectoryExists(dirname($file));
        $filesystem->put($file, $this->import($content, $imports));

        $this->info('Registered the form components with Alpine in '.Str::after($file, base_path().DIRECTORY_SEPARATOR).'.');

        if ($added) {
            $this->info('Added Alpine to package.json.');
        }

        $this->info('Run "'.($added ? 'npm install && ' : '').'npm run build" to bundle them.');

        return self::SUCCESS;
    }

    /**
     * Script starting Alpine, falling back to the app script.
     */
    private function entry(): string
    {
        $app = resource_path('js'.DIRECTORY_SEPARATOR.'app.js');
        $files = [$app];

        if ($this->filesystem->isDirectory(resource_path('js'))) {
            foreach ($this->filesystem->allFiles(resource_path('js')) as $file) {
                if ($file->getExtension() === 'js') {
                    $files[] = $file->getPathname();
                }
            }
        }

        foreach ($files as $file) {
            if ($this->filesystem->exists($file) && preg_match(self::START, $this->filesystem->get($file)) === 1) {
                return $file;
            }
        }

        return $app;
    }

    /**
     * Configured components that ship a script.
     *
     * @return list<string>
     */
    private function names(): array
    {
        /** @var array<string, class-string> $components */
        $components = config('form_components.components') ?: [];
        $names = [];

        foreach (glob(dirname(__DIR__, 2).'/Components/*/resources/js/*.js') ?: [] as $script) {
            $name = basename($script, '.js');

            if (array_key_exists($name, $components)) {
                $names[] = $name;
            }
        }

        return $names;
    }

    /**
     * Alpine data name of a component.
     */
    private function data(string $name): string
    {
        return 'form_'.str_replace('-', '_', $name);
    }

    /**
     * Lines registering the components with Alpine.
     *
     * @param  list<string>  $names
     */
    private function registrations(array $names, string $namespace, string $alpine, string $indent = ''): string
    {
        return implode("\n", array_map(
            fn (string $name): string => $indent.$alpine.".data('".$this->data($name)."', ".$namespace.'.'.Str::camel($name).');',
            $names,
        ));
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
     * Add import statements after the script's last import.
     *
     * @param  list<string>  $imports
     */
    private function import(string $content, array $imports): string
    {
        $statements = implode("\n", $imports);

        if (preg_match_all('/^import\b[^;]*?[\'"][^\'"\n]*[\'"][ \t]*;?/m', $content, $matches, PREG_OFFSET_CAPTURE) === 0) {
            return $statements."\n\n".$content;
        }

        $offset = 0;

        foreach ($matches[0] as [$statement, $position]) {
            $offset = $position + strlen($statement);
        }

        return substr_replace($content, "\n".$statements, $offset, 0);
    }

    /**
     * Path of a file relative to a directory.
     */
    private function relative(string $directory, string $file): string
    {
        $from = explode('/', trim(str_replace('\\', '/', $directory), '/'));
        $to = explode('/', trim(str_replace('\\', '/', $file), '/'));

        while ($from !== [] && $to !== [] && $from[0] === $to[0]) {
            array_shift($from);
            array_shift($to);
        }

        return ($from === [] ? './' : str_repeat('../', count($from))).implode('/', $to);
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
