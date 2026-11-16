<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Password\Tests\Browser;

use Bjnstnkvc\FormComponents\Tests\Browser\TestCase;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;

class PasswordTest extends TestCase
{
    /**
     * Selector of the icon that shows the password.
     */
    private const string SHOW = 'label[for="password_column_bottom"] [x-ref="show"]';

    /**
     * Selector of the icon that hides the password.
     */
    private const string HIDE = 'label[for="password_column_bottom"] [x-ref="hide"]';

    #[Test]
    public function it_opens_the_notch_when_a_floating_title_rises(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/');

            $this->assertRestingTitle($browser, 'password_floating_squared');

            $browser->type('password_floating_squared', 'secret');

            $this->assertRisenTitle($browser, 'password_floating_squared');
        });
    }

    #[Test]
    public function it_hides_the_password_by_default(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->assertAttribute('#password_column_bottom', 'type', 'password')
                ->assertVisible(self::SHOW)
                ->assertMissing(self::HIDE);
        });
    }

    #[Test]
    public function it_reveals_the_password(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->type('password_column_bottom', 'secret')
                ->click(self::SHOW)
                ->assertAttribute('#password_column_bottom', 'type', 'text')
                ->assertInputValue('password_column_bottom', 'secret')
                ->assertMissing(self::SHOW)
                ->assertVisible(self::HIDE);
        });
    }

    #[Test]
    public function it_hides_the_password_again(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->click(self::SHOW)
                ->click(self::HIDE)
                ->assertAttribute('#password_column_bottom', 'type', 'password')
                ->assertVisible(self::SHOW)
                ->assertMissing(self::HIDE);
        });
    }

    #[Test]
    public function it_hides_the_error_once_a_password_is_typed(): void
    {
        $this->serveWith('form_components.interactive', true);

        $this->browse(function (Browser $browser): void {
            $browser->visit('/')->type('password_error', 'secret');

            $this->assertValid($browser, 'password_error');
            $this->assertValidTitle($browser, 'password_error');
        });
    }

    #[Test]
    public function it_shows_the_error_again_once_the_password_is_removed(): void
    {
        $this->serveWith('form_components.interactive', true);

        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->type('password_error', 's')
                ->keys('#password_error', '{backspace}');

            $this->assertInvalid($browser, 'password_error');
            $this->assertInvalidTitle($browser, 'password_error');
        });
    }
}
