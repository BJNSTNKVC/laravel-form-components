<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Input\Tests\Feature;

use Bjnstnkvc\FormComponents\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class InputTest extends TestCase
{
    #[Test]
    public function it_renders_a_text_input(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::input name="email" />')
            ->assertSee('type="text"', false)
            ->assertSee('name="email"', false)
            ->assertSee('label-type="column"', false);
    }
}
