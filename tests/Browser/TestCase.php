<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Tests\Browser;

use Bjnstnkvc\FormComponents\Tests\Concerns\LoadsThePackage;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Laravel\Dusk\Browser;
use Orchestra\Testbench\Dusk\Options;
use Orchestra\Testbench\Dusk\TestCase as Testbench;
use Override;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

use function Orchestra\Testbench\package_path;

abstract class TestCase extends Testbench
{
    use LoadsThePackage;

    /**
     * Talk to Selenium using the W3C WebDriver protocol.
     */
    #[Override]
    public static function setUpBeforeClass(): void
    {
        Options::$w3cCompliant = true;

        parent::setUpBeforeClass();
    }

    /**
     * Markup of the components the tests interact with.
     */
    abstract protected function page(): string;

    /**
     * Validation errors shown on the page, keyed by field name.
     *
     * @return array<string, string>
     */
    protected function errors(): array
    {
        return [];
    }

    /**
     * Script registering the component data with Alpine.
     */
    protected function script(): string
    {
        return 'app';
    }

    /**
     * Serve the page, Alpine and the component scripts.
     *
     * @param  Router  $router
     */
    #[Override]
    protected function defineWebRoutes($router): void
    {
        $router->get('/', $this->render(...));

        $router->get('/js/alpine.js', fn (): BinaryFileResponse => response()->file(
            package_path('node_modules', 'alpinejs', 'dist', 'module.esm.js'),
            ['Content-Type' => 'text/javascript'],
        ));

        $router->get('/js/components/{script}.js', function (string $script): BinaryFileResponse {
            $files = glob(package_path('src', 'Components', '*', 'resources', 'js', $script.'.js')) ?: [];

            abort_if($files === [], 404);

            return response()->file($files[0], ['Content-Type' => 'text/javascript']);
        })->where('script', '[a-z-]+');

        $router->get('/js/{script}.js', fn (string $script): BinaryFileResponse => response()->file(
            __DIR__.'/resources/js/'.$script.'.js',
            ['Content-Type' => 'text/javascript'],
        ))->where('script', 'app|livewire');
    }

    /**
     * Render the page with its validation errors.
     */
    protected function render(): View
    {
        view()->share('errors', (new ViewErrorBag)->put('default', new MessageBag($this->errors())));

        return view()->file(__DIR__.'/resources/views/page.blade.php', [
            'content' => Blade::render($this->page()),
            'script' => $this->script(),
        ]);
    }

    /**
     * Serve the page with a config value changed.
     */
    protected function serveWith(string $key, mixed $value): void
    {
        $this->beforeServingApplication(function (Application $app, Repository $config) use ($key, $value): void {
            $config->set($key, $value);
        });
    }

    /**
     * Computed value of a CSS property on an element.
     */
    protected function style(Browser $browser, string $selector, string $property): string
    {
        $value = $browser->script(sprintf(
            'return getComputedStyle(document.querySelector(%s)).getPropertyValue(%s);',
            json_encode($selector),
            json_encode($property),
        ))[0];

        $this->assertIsString($value);

        return $value;
    }

    /**
     * Assert the field is marked invalid with its error shown.
     */
    protected function assertInvalid(Browser $browser, string $name): void
    {
        $browser->assertAttribute('label[for="'.$name.'"]', 'invalid', '')
            ->assertVisible('label[for="'.$name.'"] [role="alert"]');
    }

    /**
     * Assert the field is marked valid with its error hidden.
     */
    protected function assertValid(Browser $browser, string $name): void
    {
        $browser->assertAttributeMissing('label[for="'.$name.'"]', 'invalid')
            ->assertMissing('label[for="'.$name.'"] [role="alert"]');
    }

    /**
     * Assert the field title is coloured as invalid.
     */
    protected function assertInvalidTitle(Browser $browser, string $name): void
    {
        $this->assertTrue($this->hasInvalidTitle($browser, $name), 'The ['.$name.'] title is not marked invalid.');
    }

    /**
     * Assert the field title is not coloured as invalid.
     */
    protected function assertValidTitle(Browser $browser, string $name): void
    {
        $this->assertFalse($this->hasInvalidTitle($browser, $name), 'The ['.$name.'] title is marked invalid.');
    }

    /**
     * Determine whether the field title takes the error colour.
     */
    private function hasInvalidTitle(Browser $browser, string $name): bool
    {
        $browser->waitUntil(sprintf(
            'document.querySelector(%s).getAnimations().length === 0',
            json_encode('label[for="'.$name.'"] > span:not([role])'),
        ), 2);

        return $this->style($browser, 'label[for="'.$name.'"] > span:not([role])', 'color')
            === $this->style($browser, 'label[for="'.$name.'"] [role="alert"]', 'color');
    }

    /**
     * Assert the floating title of the field has risen.
     */
    protected function assertRisenTitle(Browser $browser, string $name): void
    {
        $this->assertStyleSettles($browser, 'label[for="'.$name.'"] legend', 'max-width', '100%');
    }

    /**
     * Assert the floating title of the field rests inside it.
     */
    protected function assertRestingTitle(Browser $browser, string $name): void
    {
        $this->assertStyleSettles($browser, 'label[for="'.$name.'"] legend', 'max-width', '0.01px');
    }

    /**
     * Assert a CSS property reaches a value once transitions finish.
     */
    protected function assertStyleSettles(Browser $browser, string $selector, string $property, string $value): void
    {
        $browser->waitUntil(sprintf(
            'getComputedStyle(document.querySelector(%s)).getPropertyValue(%s) === %s',
            json_encode($selector),
            json_encode($property),
            json_encode($value),
        ), 2);

        $this->assertSame($value, $this->style($browser, $selector, $property));
    }
}
