<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Radio\Tests\Feature;

use Bjnstnkvc\FormComponents\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class RadioTest extends TestCase
{
    #[Test]
    public function it_renders_a_radio(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::radio name="plan" />')
            ->assertSee('type="radio"', false)
            ->assertSee('name="plan"', false);
    }

    #[Test]
    public function it_renders_a_switch_by_default(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::radio name="plan" />')
            ->assertSee('fc:hidden')
            ->assertSee('<i class="fc:[grid-area:input]', false);
    }

    #[Test]
    public function it_renders_a_plain_radio_when_the_switch_is_off(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::radio name="plan" switch="false" />')
            ->assertSee('fc:size-4')
            ->assertDontSee('<i ', false);
    }

    #[Test]
    public function it_is_centred_by_default(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::radio name="plan" />')
            ->assertSee('fc:mx-auto')
            ->assertDontSee('fc:mr-auto');
    }

    #[Test]
    public function it_can_be_aligned_to_the_left(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::radio name="plan" position="left" />')
            ->assertSee('fc:mr-auto')
            ->assertDontSee('fc:mx-auto');
    }
}
