<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\DateTime\Tests\Feature;

use Bjnstnkvc\FormComponents\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class DateTimeTest extends TestCase
{
    #[Test]
    public function it_renders_a_date_time_input(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::date-time name="starts_at" />')
            ->assertSee('type="datetime-local"', false)
            ->assertSee('name="starts_at"', false);
    }
}
