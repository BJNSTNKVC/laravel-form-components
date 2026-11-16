<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Tests\Browser\Assets;

use Bjnstnkvc\FormComponents\Tests\Browser\TestCase;
use Illuminate\Http\Response;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Vite;
use Laravel\Dusk\Browser;
use Override;
use PHPUnit\Framework\Attributes\Test;

class StylesheetTest extends TestCase
{
    /**
     * A button the stylesheet colours.
     */
    #[Override]
    protected function page(): string
    {
        return <<<'BLADE'
            <x-form::button name="submit" />
            BLADE;
    }

    /**
     * Also serve the page under a strict content security policy.
     *
     * @param  Router  $router
     */
    #[Override]
    protected function defineWebRoutes($router): void
    {
        parent::defineWebRoutes($router);

        $router->get('/csp', function (): Response {
            Vite::useCspNonce();

            return response($this->render())->header('Content-Security-Policy', sprintf("style-src 'nonce-%s'", Vite::cspNonce()));
        });
    }

    #[Test]
    public function it_styles_components_without_a_stylesheet_in_the_page(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/');

            $this->assertSame('rgb(0, 0, 0)', $this->style($browser, 'button[type="submit"]', 'background-color'));
        });
    }

    #[Test]
    public function it_styles_components_under_a_strict_content_security_policy(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/csp');

            $this->assertSame('rgb(0, 0, 0)', $this->style($browser, 'button[type="submit"]', 'background-color'));
        });
    }
}
