<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Select\Tests\Feature;

use Bjnstnkvc\FormComponents\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class SelectTest extends TestCase
{
    #[Test]
    public function it_renders_a_select(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::select name="country" values="Serbia, Spain" />')
            ->assertSee('<select', false)
            ->assertSee('name="country"', false)
            ->assertSee('Serbia', false)
            ->assertSee('Spain', false);
    }
}
