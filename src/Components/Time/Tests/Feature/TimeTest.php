<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Time\Tests\Feature;

use Bjnstnkvc\FormComponents\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class TimeTest extends TestCase
{
    #[Test]
    public function it_renders_a_time_input(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::time name="alarm" />')
            ->assertSee('type="time"', false)
            ->assertSee('name="alarm"', false);
    }
}
