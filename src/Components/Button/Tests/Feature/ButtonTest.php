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
}
