<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Date\Tests\Browser;

use Bjnstnkvc\FormComponents\Tests\Browser\TestCase;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;

class DateTest extends TestCase
{
    #[Test]
    public function it_raises_the_floating_title_once_a_date_is_entered(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/');

            $this->assertRestingTitle($browser, 'date_floating_squared');

            $browser->keys('#date_floating_squared', '02152026')
                ->assertInputValue('date_floating_squared', '2026-02-15')
                ->assertAttribute('#date_floating_squared', 'value', '2026-02-15');

            $this->assertRisenTitle($browser, 'date_floating_squared');
        });
    }

    #[Test]
    public function it_hides_the_error_once_a_date_is_entered(): void
    {
        $this->serveWith('form_components.interactive', true);

        $this->browse(function (Browser $browser): void {
            $browser->visit('/')->keys('#date_error', '02152026');

            $this->assertValid($browser, 'date_error');
        });
    }

    #[Test]
    public function it_shows_the_error_again_once_the_date_is_removed(): void
    {
        $this->serveWith('form_components.interactive', true);

        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->keys('#date_error', '02152026')
                ->keys('#date_error', '{backspace}');

            $this->assertInvalid($browser, 'date_error');
        });
    }
}
