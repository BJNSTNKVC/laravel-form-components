<?php

namespace Bjnstnkvc\FormComponents\Tests\Feature;

use Bjnstnkvc\FormComponents\FormComponentsServiceProvider;
use Bjnstnkvc\FormComponents\Tests\TestCase;
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
    public function it_renders_a_component(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::input name="email" />')
            ->assertSee('<input', false)
            ->assertSee('name="email"', false)
            ->assertSee('label-type="column"', false);
    }
}
