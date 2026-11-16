<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Tests\Feature\Http\Controllers;

use Bjnstnkvc\FormComponents\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StylesheetControllerTest extends TestCase
{
    #[Test]
    #[DataProvider('stylesheets')]
    public function it_serves_a_stylesheet(string $file): void
    {
        $response = $this->get('/form-components/'.$file)
            ->assertOk()
            ->assertHeader('Content-Type', 'text/css; charset=UTF-8')
            ->assertHeader('Cache-Control', 'immutable, max-age=31536000, public');

        $this->assertInstanceOf(BinaryFileResponse::class, $response->baseResponse);
        $this->assertSame(realpath($this->stylesheetPath($file)), $response->baseResponse->getFile()->getRealPath());
    }

    #[Test]
    public function it_serves_nothing_but_its_stylesheets(): void
    {
        $this->get('/form-components/form-components.js')->assertNotFound();
    }

    #[Test]
    public function it_serves_stylesheets_without_cookies(): void
    {
        $this->get('/form-components/form-components.min.css')->assertCookieMissing('laravel_session');
    }

    /**
     * Stylesheets the package serves.
     *
     * @return iterable<string, array{string}>
     */
    public static function stylesheets(): iterable
    {
        yield 'minified' => ['form-components.min.css'];
        yield 'readable' => ['form-components.css'];
    }

    /**
     * Path of a stylesheet the package ships.
     */
    private function stylesheetPath(string $file): string
    {
        return dirname(__DIR__, 4).'/src/public/css/'.$file;
    }
}
