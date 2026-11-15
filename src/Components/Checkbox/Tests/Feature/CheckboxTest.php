<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Checkbox\Tests\Feature;

use Bjnstnkvc\FormComponents\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CheckboxTest extends TestCase
{
    #[Test]
    public function it_renders_a_checkbox(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::checkbox name="remember" />')
            ->assertSee('type="checkbox"', false)
            ->assertSee('name="remember"', false);
    }

    #[Test]
    public function it_is_centred_by_default(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::checkbox name="remember" />')
            ->assertSee('fc:mx-auto')
            ->assertDontSee('fc:mr-auto');
    }

    #[Test]
    public function it_can_be_aligned_to_the_left(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::checkbox name="remember" position="left" />')
            ->assertSee('fc:mr-auto')
            ->assertDontSee('fc:mx-auto');
    }

    #[Test]
    public function it_renders_a_switch_by_default(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::checkbox name="remember" />')
            ->assertSee('fc:hidden')
            ->assertSee('<i class="fc:[grid-area:input]', false);
    }

    #[Test]
    public function it_renders_a_plain_checkbox_when_the_switch_is_off(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::checkbox name="remember" switch="false" />')
            ->assertSee('fc:size-4')
            ->assertDontSee('<i ', false);
    }
}
