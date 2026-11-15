<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Password\Tests\Feature;

use Bjnstnkvc\FormComponents\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PasswordTest extends TestCase
{
    #[Test]
    public function it_renders_a_password_input(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::password name="password" />')
            ->assertSee('type="password"', false)
            ->assertSee('name="password"', false);
    }
}
