<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Helpers;

use Closure;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class Registrar
{
    /**
     * Line where Alpine, or Livewire bringing it, starts.
     */
    public const string START = '/^([ \t]*)((?:window\.)?(?:Alpine|Livewire))\.start\(\s*\)/m';

    /**
     * Name the app imports the component scripts under.
     */
    private const string NAMESPACE = 'form';

    /**
     * Create a new registrar instance.
     */
    public function __construct(private readonly Filesystem $filesystem, private readonly Publisher $publisher) {}

    /**
     * Script starting Alpine, falling back to the app script.
     *
     * @throws FileNotFoundException
     */
    public function entry(): string
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
     * Alpine data name of a component.
     */
    public function data(string $name): string
    {
        return 'form_'.str_replace('-', '_', $name);
    }

    /**
     * Determine whether a script registers a component.
     */
    public function registers(string $content, string $name): bool
    {
        return preg_match('/Alpine\.data\(\s*[\'"]'.$this->data($name).'[\'"]/', $content) === 1;
    }

    /**
     * Package script of a component, with the imports it needs.
     *
     * @return array{string, list<string>}
     */
    public function package(string $content, string $file, string $name): array
    {
        $index = $this->relative(dirname($file), (string) realpath(__DIR__.'/../resources/js/index.js'));

        if (preg_match('/^import\s+\*\s+as\s+(\w+)\s+from\s+[\'"]'.preg_quote($index, '/').'[\'"]/m', $content, $import) === 1) {
            return [$import[1].'.'.Str::camel($name), []];
        }

        return [self::NAMESPACE.'.'.Str::camel($name), ['import * as '.self::NAMESPACE." from '".$index."';"]];
    }

    /**
     * Copied script of a component, with the imports it needs.
     *
     * @return array{string, list<string>}
     */
    public function copied(string $file, string $name): array
    {
        return [$this->identifier($name), [$this->copiedImport($file, $name)]];
    }

    /**
     * Register copied scripts in place of the package ones.
     *
     * @param  list<string>  $names
     *
     * @throws FileNotFoundException
     */
    public function useCopies(array $names): ?string
    {
        return $this->repoint($names, fn (string $content, string $file, string $name): array => $this->copied($file, $name));
    }

    /**
     * Register package scripts in place of the copied ones.
     *
     * @param  list<string>  $names
     *
     * @throws FileNotFoundException
     */
    public function usePackage(array $names): ?string
    {
        return $this->repoint($names, fn (string $content, string $file, string $name): array => $this->package($content, $file, $name));
    }

    /**
     * Add import statements after the script's last import.
     *
     * @param  list<string>  $imports
     */
    public function import(string $content, array $imports): string
    {
        $imports = array_filter(array_unique($imports), fn (string $import): bool => ! str_contains($content, $import));

        if ($imports === []) {
            return $content;
        }

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
     * Point registrations at other scripts, returning the changed file.
     *
     * @param  list<string>  $names
     * @param  Closure(string, string, string): array{string, list<string>}  $source
     *
     * @throws FileNotFoundException
     */
    private function repoint(array $names, Closure $source): ?string
    {
        $file = $this->entry();

        if ($names === [] || ! $this->filesystem->exists($file)) {
            return null;
        }

        $original = $this->filesystem->get($file);
        $content = $original;
        $imports = [];

        foreach ($names as $name) {
            [$script, $needed] = $source($content, $file, $name);
            $pointed = $this->point($content, $name, $script);

            if ($pointed !== $content) {
                $content = $this->forget($pointed, $file, $name);
                $imports = [...$imports, ...$needed];
            }
        }

        $content = $this->import($content, $imports);

        if ($content === $original) {
            return null;
        }

        $this->filesystem->put($file, $content);

        return $file;
    }

    /**
     * Point a component's registration at another script.
     */
    private function point(string $content, string $name, string $script): string
    {
        return (string) preg_replace(
            '/(Alpine\.data\(\s*([\'"])'.$this->data($name).'\2\s*,\s*)[\w$.]+(?=\s*\))/',
            '${1}'.$script,
            $content,
        );
    }

    /**
     * Remove the import of a copied script no longer used.
     */
    private function forget(string $content, string $file, string $name): string
    {
        $forgotten = (string) preg_replace('/^'.preg_quote($this->copiedImport($file, $name), '/').'\R/m', '', $content);

        return preg_match('/\b'.$this->identifier($name).'\b/', $forgotten) === 1 ? $content : $forgotten;
    }

    /**
     * Name the app imports a copied script under.
     */
    private function identifier(string $name): string
    {
        return self::NAMESPACE.Str::studly($name);
    }

    /**
     * Statement importing the copied script of a component.
     */
    private function copiedImport(string $file, string $name): string
    {
        return 'import '.$this->identifier($name)." from '".$this->relative(dirname($file), $this->publisher->publishScript($name))."';";
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
}
