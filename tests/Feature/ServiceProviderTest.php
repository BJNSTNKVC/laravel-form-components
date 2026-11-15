<?php

namespace Bjnstnkvc\FormComponents\Tests\Feature;

use Bjnstnkvc\FormComponents\FormComponentsServiceProvider;
use Bjnstnkvc\FormComponents\Tests\TestCase;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use PHPUnit\Framework\Attributes\Test;

class ServiceProviderTest extends TestCase
{
    #[Test]
    public function it_registers_the_service_provider(): void
    {
        $this->assertNotNull($this->app);
        $this->assertArrayHasKey(FormComponentsServiceProvider::class, $this->app->getLoadedProviders());
    }

    #[Test]
    public function it_registers_every_configured_component(): void
    {
        $aliases = Blade::getClassComponentAliases();

        foreach ($this->components() as $alias => $class) {
            $this->assertSame($class, $aliases['form::'.$alias] ?? null);
        }
    }

    #[Test]
    public function it_loads_each_component_view_from_the_component_folder(): void
    {
        foreach (array_keys($this->components()) as $alias) {
            $this->assertTrue(View::exists('form-components::'.$alias), sprintf('View [%s] was not found.', $alias));
        }
    }

    #[Test]
    public function it_links_the_minified_stylesheet_by_default(): void
    {
        $this->assertSame(
            "<link href='".asset('vendor/form-components/css/form-components.min.css')."' rel='stylesheet'>",
            Blade::compileString('@componentStyles')
        );
    }

    #[Test]
    public function it_links_the_full_stylesheet_when_asked(): void
    {
        $this->assertSame(
            "<link href='".asset('vendor/form-components/css/form-components.css')."' rel='stylesheet'>",
            Blade::compileString("@componentStyles('full')")
        );
    }

    #[Test]
    public function it_links_the_minified_script_by_default(): void
    {
        $this->assertSame(
            "<script src='".asset('vendor/form-components/js/form-components.min.js')."' defer></script>",
            Blade::compileString('@componentScripts')
        );
    }

    #[Test]
    public function it_links_the_full_script_when_asked(): void
    {
        $this->assertSame(
            "<script src='".asset('vendor/form-components/js/form-components.js')."' defer></script>",
            Blade::compileString("@componentScripts('full')")
        );
    }

    /**
     * Components listed in the package configuration.
     *
     * @return array<string, class-string>
     */
    private function components(): array
    {
        /** @var array<string, class-string> $components */
        $components = config('form_components.components');

        return $components;
    }
}
