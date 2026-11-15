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
}
