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
}
