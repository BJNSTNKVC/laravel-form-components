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
