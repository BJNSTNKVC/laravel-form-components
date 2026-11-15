<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Base\Tests\Feature;

use Bjnstnkvc\FormComponents\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class BaseTest extends TestCase
{
    #[Test]
    public function it_renders_a_form(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::base action="/login" />')
            ->assertSee('<form', false)
            ->assertSee('action="/login"', false)
            ->assertSee('method="POST"', false)
            ->assertSee('name="_token"', false);
    }
}
