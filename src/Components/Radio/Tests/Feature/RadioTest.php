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
}
