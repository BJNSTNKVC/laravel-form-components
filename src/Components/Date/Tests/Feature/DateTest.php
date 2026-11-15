<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Date\Tests\Feature;

use Bjnstnkvc\FormComponents\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class DateTest extends TestCase
{
    #[Test]
    public function it_renders_a_date_input(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::date name="birthday" />')
            ->assertSee('type="date"', false)
            ->assertSee('name="birthday"', false);
    }
}
