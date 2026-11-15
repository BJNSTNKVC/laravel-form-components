<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Textarea\Tests\Feature;

use Bjnstnkvc\FormComponents\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class TextareaTest extends TestCase
{
    #[Test]
    public function it_renders_a_textarea(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::textarea name="bio" />')
            ->assertSee('<textarea', false)
            ->assertSee('name="bio"', false);
    }
}
