<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Button\Tests\Feature;

use Bjnstnkvc\FormComponents\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ButtonTest extends TestCase
{
    #[Test]
    public function it_renders_a_button(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::button title="Save" />')
            ->assertSee('<button', false)
            ->assertSee('type="submit"', false)
            ->assertSee('Save', false);
    }

    #[Test]
    public function it_renders_a_link(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::button title="Home" link="/" />')
            ->assertSee('<a', false)
            ->assertSee('href="/"', false)
            ->assertDontSee('<button', false);
    }

    #[Test]
    public function it_renders_rounded_corners_by_default(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::button title="Save" />')
            ->assertSee('fc:rounded-3xl');
    }

    #[Test]
    public function it_renders_the_requested_corner_radius(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::button title="Save" border-radius="squared" />')
            ->assertSee('fc:rounded-none')
            ->assertDontSee('fc:rounded-3xl');
    }
}
