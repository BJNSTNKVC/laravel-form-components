<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;

class PlaygroundTest extends TestCase
{
    #[Test]
    public function it_loads_the_playground(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->assertTitle('Form Components Playground')
                ->assertPresent('input[name="input_column_bottom"]')
                ->assertPresent('button[type="submit"]');
        });
    }
}
