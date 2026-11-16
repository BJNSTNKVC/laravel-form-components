<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Select\Tests\Browser;

use Bjnstnkvc\FormComponents\Tests\Browser\TestCase;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;

class SelectTest extends TestCase
{
    #[Test]
    public function it_raises_the_floating_title_once_an_option_is_chosen(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/');

            $this->assertRestingTitle($browser, 'select_floating_squared');

            $browser->keys('#select_floating_squared', '{arrow_down}')
                ->assertAttribute('#select_floating_squared', 'value', 'option-b');

            $this->assertRisenTitle($browser, 'select_floating_squared');
        });
    }

    #[Test]
    public function it_hides_the_error_once_an_option_is_chosen(): void
    {
        $this->serveWith('form_components.interactive', true);

        $this->browse(function (Browser $browser): void {
            $browser->visit('/')->keys('#select_error', '{arrow_down}');

            $this->assertValid($browser, 'select_error');
        });
    }

    #[Test]
    public function it_shows_the_error_again_once_the_placeholder_is_chosen(): void
    {
        $this->serveWith('form_components.interactive', true);

        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->keys('#select_error', '{arrow_down}')
                ->keys('#select_error', '{arrow_up}');

            $this->assertInvalid($browser, 'select_error');
        });
    }
}
