<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Tests\Feature\Integration;

use Bjnstnkvc\FormComponents\FormComponentsServiceProvider;
use Bjnstnkvc\FormComponents\Tests\Feature\Console\Commands\TestCase;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Override;
use PHPUnit\Framework\Attributes\Test;

class CopiedComponentsTest extends TestCase
{
    /**
     * Forget views compiled against the copied components.
     */
    #[Override]
    protected function tearDown(): void
    {
        $this->command('view:clear')->run();

        parent::tearDown();
    }

    #[Test]
    public function it_renders_copied_components_beside_the_package_ones(): void
    {
        $this->write('resources/js/app.js', $this->installedScript());

        $this->command('components:publish', ['component' => ['Password', 'Error']])->assertSuccessful();

        $this->mark('password', 'x-data="form_password"');
        $this->mark('error', 'role="alert"');
        $this->load();

        Route::get('/page', fn (): string => Blade::render(<<<'BLADE'
            <html>
            <head></head>
            <body>
                <x-form::password name="secret" />
                <x-form::input name="name" />
            </body>
            </html>
            BLADE));

        $page = (string) $this->withViewErrors(['secret' => 'Wrong.', 'name' => 'Required.'])->get('/page')->getContent();

        $this->assertStringContainsString('x-data="form_password" data-copied="password"', $page);
        $this->assertStringContainsString('x-data="form_input"', $page);
        $this->assertSame(2, substr_count($page, 'data-copied="error"'));
        $this->assertSame(1, substr_count($page, 'rel="stylesheet"'));
        $this->assertStringContainsString("Alpine.data('form_password', formPassword);", $this->script('resources/js/app.js'));
    }

    /**
     * Mark a copied view after the given markup.
     */
    private function mark(string $view, string $after): void
    {
        $path = $this->publisher->publishView($view);

        file_put_contents($path, str_replace($after, $after.' data-copied="'.$view.'"', (string) file_get_contents($path)));
    }

    /**
     * Boot the package again with the copied components.
     */
    private function load(): void
    {
        foreach (glob($this->publisher->classPath.'*.php') ?: [] as $class) {
            require_once $class;
        }

        config()->set('form_components', require $this->publisher->configPath);

        $this->app?->register(FormComponentsServiceProvider::class, true);

        View::addLocation($this->root.'/resources/views');
    }
}
