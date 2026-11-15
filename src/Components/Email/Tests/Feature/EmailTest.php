<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Email\Tests\Feature;

use Bjnstnkvc\FormComponents\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class EmailTest extends TestCase
{
    #[Test]
    public function it_renders_an_email_input(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::email name="email" />')
            ->assertSee('type="email"', false)
            ->assertSee('name="email"', false);
    }

    #[Test]
    public function it_renders_a_bottom_border_by_default(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::email name="email" title="Email" />')
            ->assertSee('fc:border-b-2')
            ->assertDontSee('fc:border-2');
    }

    #[Test]
    public function it_renders_a_full_rounded_border(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::email name="email" title="Email" border="full" border-radius="rounded" />')
            ->assertSee('fc:border-2')
            ->assertSee('fc:rounded-full')
            ->assertDontSee('fc:border-b-2');
    }

    #[Test]
    public function it_notches_the_border_for_a_floating_title(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::email name="email" title="Email" label-type="floating" border="full" />')
            ->assertSee('<fieldset', false)
            ->assertSee('aria-hidden="true"', false)
            ->assertSee('<legend><span class="fc:px-2">Email</span></legend>', false);
    }

    #[Test]
    public function it_does_not_notch_a_border_without_a_floating_title(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::email name="email" title="Email" border="full" />')
            ->assertDontSee('<fieldset', false);

        $this->withViewErrors([])
            ->blade('<x-form::email name="email" title="Email" label-type="floating" />')
            ->assertDontSee('<fieldset', false);
    }

    #[Test]
    public function it_hides_the_placeholder_behind_a_floating_title(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::email name="email" title="Email" label-type="floating" />')
            ->assertSee('fc:focus:placeholder:text-transparent');
    }
}
