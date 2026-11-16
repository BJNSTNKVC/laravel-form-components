<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Time\Tests\Browser;

use Bjnstnkvc\FormComponents\Tests\Browser\TestCase;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;

class TimeTest extends TestCase
{
    #[Test]
    public function it_raises_the_floating_title_once_a_time_is_entered(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/');

            $this->assertRestingTitle($browser, 'time_floating_squared');

            $browser->keys('#time_floating_squared', '103045AM')
                ->assertInputValue('time_floating_squared', '10:30:45')
                ->assertAttribute('#time_floating_squared', 'value', '10:30:45');

            $this->assertRisenTitle($browser, 'time_floating_squared');
        });
    }

    #[Test]
    public function it_hides_the_error_once_a_time_is_entered(): void
    {
        $this->serveWith('form_components.interactive', true);

        $this->browse(function (Browser $browser): void {
            $browser->visit('/')->keys('#time_error', '103045AM');

            $this->assertValid($browser, 'time_error');
            $this->assertValidTitle($browser, 'time_error');
        });
    }

    #[Test]
    public function it_shows_the_error_again_once_the_time_is_removed(): void
    {
        $this->serveWith('form_components.interactive', true);

        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->keys('#time_error', '103045AM')
                ->keys('#time_error', '{backspace}');

            $this->assertInvalid($browser, 'time_error');
            $this->assertInvalidTitle($browser, 'time_error');
        });
    }
}
