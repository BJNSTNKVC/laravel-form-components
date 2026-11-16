<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Tests\Feature\Assets;

use Bjnstnkvc\FormComponents\Tests\TestCase;
use Closure;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
use Override;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;

class StylesheetTest extends TestCase
{
    /**
     * Share the error bag the web middleware would.
     */
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->withViewErrors([]);
    }

    #[Test]
    public function it_links_the_minified_stylesheet_with_the_directive(): void
    {
        $this->assertSame($this->link('form-components.min.css'), Blade::render('@componentStyles'));
    }

    #[Test]
    public function it_links_the_readable_stylesheet_with_the_directive_when_asked(): void
    {
        $this->assertSame($this->link('form-components.css'), Blade::render("@componentStyles('full')"));
    }

    #[Test]
    public function it_versions_the_link_by_the_stylesheet_contents(): void
    {
        $this->assertStringContainsString(
            '?id='.substr((string) hash_file('xxh3', $this->stylesheetPath('form-components.min.css')), 0, 8).'"',
            Blade::render('@componentStyles'),
        );
    }

    #[Test]
    public function it_adds_the_csp_nonce_to_the_directive_link(): void
    {
        Vite::useCspNonce('n0nce');

        $this->assertStringEndsWith(' nonce="n0nce">', Blade::render('@componentStyles'));
    }

    #[Test]
    public function it_links_the_stylesheet_at_the_start_of_a_page_rendering_a_component(): void
    {
        $this->page('<html><head lang="en"><title>Page</title></head><body><x-form::input name="name" /></body></html>');

        $this->get('/page')->assertSee('<head lang="en">'.$this->link('form-components.min.css').'<title>', false);
    }

    #[Test]
    public function it_links_the_stylesheet_into_a_page_rendering_a_published_component(): void
    {
        $views = sys_get_temp_dir().'/form-components-'.uniqid();

        (new Filesystem)->ensureDirectoryExists($views.'/components/form');
        file_put_contents($views.'/components/form/field.blade.php', '<input name="name">');
        View::addLocation($views);

        $this->page('<html><head></head><body>@include("components.form.field")</body></html>');

        $this->get('/page')->assertSee($this->link('form-components.min.css'), false);

        (new Filesystem)->deleteDirectory($views);
    }

    #[Test]
    public function it_adds_the_csp_nonce_to_the_injected_link(): void
    {
        Vite::useCspNonce('n0nce');

        $this->page('<html><head></head><body><x-form::input name="name" /></body></html>');

        $this->get('/page')->assertSee('<head><link href="'.e($this->url('form-components.min.css')).'" rel="stylesheet" nonce="n0nce">', false);
    }

    #[Test]
    public function it_keeps_the_view_of_the_page_it_links_the_stylesheet_into(): void
    {
        $this->get('/')
            ->assertViewHas('errors')
            ->assertSee($this->link('form-components.min.css'), false);
    }

    #[Test]
    public function it_leaves_a_page_without_components_alone(): void
    {
        $this->page('<html><head></head><body>Nothing to style</body></html>');

        $this->get('/page')->assertSee('<html><head></head><body>Nothing to style</body></html>', false);
    }

    #[Test]
    public function it_leaves_a_page_linking_the_stylesheet_itself_alone(): void
    {
        $this->page('<html><head>@componentStyles(\'full\')</head><body><x-form::input name="name" /></body></html>');

        $content = (string) $this->get('/page')->getContent();

        $this->assertSame(1, substr_count($content, '<link'));
        $this->assertStringContainsString($this->link('form-components.css'), $content);
    }

    #[Test]
    public function it_leaves_pages_alone_when_injection_is_turned_off(): void
    {
        config(['form_components.inject_styles' => false]);

        $this->page('<html><head></head><body><x-form::input name="name" /></body></html>');

        $this->get('/page')->assertDontSee('<link', false);
    }

    #[Test]
    #[DataProvider('responsesWithoutAHead')]
    public function it_leaves_responses_without_an_html_head_alone(Closure $response): void
    {
        Route::get('/page', $response);

        $this->get('/page')->assertDontSee('stylesheet', false);
    }

    /**
     * Responses rendering a component without an HTML head.
     *
     * @return iterable<string, array{Closure(): Response}>
     */
    public static function responsesWithoutAHead(): iterable
    {
        $page = '<html><head></head><body><x-form::input name="name" /></body></html>';

        yield 'fragment' => [fn (): Response => response(Blade::render('<x-form::input name="name" />'))];
        yield 'header only' => [fn (): Response => response(Blade::render('<header></header><x-form::input name="name" />'))];
        yield 'plain text' => [fn (): Response => response(Blade::render($page), 200, ['Content-Type' => 'text/plain'])];
        yield 'json' => [fn (): Response => response()->json(['html' => Blade::render($page)])];
    }

    /**
     * Serve a page rendering the given Blade template.
     */
    private function page(string $template): void
    {
        Route::get('/page', fn (): string => Blade::render($template));
    }

    /**
     * Link tag the package renders for a stylesheet.
     */
    private function link(string $file): string
    {
        return '<link href="'.e($this->url($file)).'" rel="stylesheet">';
    }

    /**
     * Versioned URL the package serves a stylesheet from.
     */
    private function url(string $file): string
    {
        return url('/form-components/'.$file).'?id='.substr((string) hash_file('xxh3', $this->stylesheetPath($file)), 0, 8);
    }

    /**
     * Path of a stylesheet the package ships.
     */
    private function stylesheetPath(string $file): string
    {
        return dirname(__DIR__, 3).'/src/public/css/'.$file;
    }
}
