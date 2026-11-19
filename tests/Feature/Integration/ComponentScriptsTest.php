<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Tests\Feature\Integration;

use Bjnstnkvc\FormComponents\Tests\TestCase;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class ComponentScriptsTest extends TestCase
{
    #[Test]
    #[DataProvider('templates')]
    public function it_keeps_scripts_out_of_the_template(string $template): void
    {
        $this->assertStringNotContainsString('<script', (string) file_get_contents($template));
    }

    #[Test]
    #[DataProvider('templates')]
    public function it_wires_behaviour_in_the_script_instead_of_the_template(string $template): void
    {
        $this->assertDoesNotMatchRegularExpression('/\s(x-on:|x-bind:|x-show|x-model|@click|:class)/', (string) file_get_contents($template));
    }

    #[Test]
    #[DataProvider('alpineComponents')]
    public function it_keeps_each_alpine_component_in_its_own_folder(string $component): void
    {
        $scripts = glob(__DIR__.'/../../../src/Components/'.Str::studly(Str::after($component, 'form_')).'/resources/js/*.js') ?: [];

        $this->assertCount(1, $scripts);

        $script = (string) file_get_contents($scripts[0]);

        $this->assertStringStartsWith('export default () => ({', $script);
        $this->assertStringNotContainsString('import ', $script);
    }

    #[Test]
    #[DataProvider('alpineComponents')]
    public function it_exports_each_alpine_component_from_the_index(string $component): void
    {
        $folder = Str::studly(Str::after($component, 'form_'));
        $scripts = glob(__DIR__.'/../../../src/Components/'.$folder.'/resources/js/*.js') ?: [];

        $this->assertCount(1, $scripts);

        $name = basename($scripts[0], '.js');

        $this->assertStringContainsString(
            'export { default as '.Str::camel($name)." } from '../../Components/".$folder.'/resources/js/'.$name.".js';",
            $this->index(),
        );
    }

    #[Test]
    public function it_exports_nothing_but_the_component_scripts_from_the_index(): void
    {
        $lines = explode("\n", trim($this->index()));

        $this->assertCount(count(glob(__DIR__.'/../../../src/Components/*/resources/js/*.js') ?: []), $lines);

        foreach ($lines as $line) {
            $this->assertMatchesRegularExpression("#^export \{ default as \w+ \} from '\.\./\.\./Components/\w+/resources/js/[a-z-]+\.js';$#", $line);
        }
    }

    /**
     * Blade templates of every component.
     *
     * @return iterable<string, array{string}>
     */
    public static function templates(): iterable
    {
        foreach (self::templatePaths() as $template) {
            yield basename($template, '.blade.php') => [$template];
        }
    }

    /**
     * Alpine components the templates ask for.
     *
     * @return iterable<string, array{string}>
     */
    public static function alpineComponents(): iterable
    {
        $components = [];

        foreach (self::templatePaths() as $template) {
            preg_match_all('/x-data="(\w+)"/', (string) file_get_contents($template), $matches);

            $components = [...$components, ...$matches[1]];
        }

        foreach (array_unique($components) as $component) {
            yield $component => [$component];
        }
    }

    /**
     * Contents of the index re-exporting every component script.
     */
    private function index(): string
    {
        return (string) file_get_contents(__DIR__.'/../../../src/resources/js/index.js');
    }

    /**
     * Paths of every component Blade template.
     *
     * @return list<string>
     */
    private static function templatePaths(): array
    {
        return glob(__DIR__.'/../../../src/Components/*/resources/views/*.blade.php') ?: [];
    }
}
