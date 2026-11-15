<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Error\Tests\Feature;

use Bjnstnkvc\FormComponents\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ErrorTest extends TestCase
{
    #[Test]
    public function it_renders_the_validation_message(): void
    {
        $this->withViewErrors(['email' => 'The email field is required.'])
            ->blade('<x-form::error name="email" />')
            ->assertSee('role="alert"', false)
            ->assertSee('The email field is required.', false);
    }
}
