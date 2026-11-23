<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Password\Tests\Browser;

use Bjnstnkvc\FormComponents\Tests\Browser\TestCase;
use Laravel\Dusk\Browser;
use Override;
use PHPUnit\Framework\Attributes\Test;

class PasswordTest extends TestCase
{
    /**
     * Selector of the button that shows the password.
     */
    private const string SHOW = 'label[for="password_column_bottom"] [x-ref="show"]';

    /**
     * Selector of the button that hides the password.
     */
    private const string HIDE = 'label[for="password_column_bottom"] [x-ref="hide"]';

    /**
     * Passwords with column and floating titles, and an invalid password.
     */
    #[Override]
    protected function page(): string
    {
        return <<<'BLADE'
            <x-form::password
                name="password_column_bottom"
                title="Password"
                label-type="column"
                border="bottom"
                border-radius="squared"
            />

            <x-form::password
                name="password_floating_squared"
                title="Password"
                label-type="floating"
                border="full"
                border-radius="squared"
            />

            <x-form::password
                name="password_error"
                title="Password"
                invalidated-title="true"
            />
            BLADE;
    }

    /**
     * Validation errors shown on the page.
     *
     * @return array<string, string>
     */
    #[Override]
    protected function errors(): array
    {
        return [
            'password_error' => 'The password field is required.',
        ];
    }

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
    public function it_moves_focus_to_the_hide_button_once_revealed(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')->click(self::SHOW);

            $this->assertFocused($browser, self::HIDE);
        });
    }

    #[Test]
    public function it_moves_focus_to_the_show_button_once_hidden(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->click(self::SHOW)
                ->click(self::HIDE);

            $this->assertFocused($browser, self::SHOW);
        });
    }

    #[Test]
    public function it_reveals_the_password_from_the_keyboard(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->keys('#password_column_bottom', '{tab}', '{enter}')
                ->assertAttribute('#password_column_bottom', 'type', 'text');
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
