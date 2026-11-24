<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Checkbox\Tests\Browser;

use Bjnstnkvc\FormComponents\Tests\Browser\TestCase;
use Laravel\Dusk\Browser;
use Override;
use PHPUnit\Framework\Attributes\Test;

class CheckboxTest extends TestCase
{
    /**
     * An invalid checkbox and a checkbox switch.
     */
    #[Override]
    protected function page(): string
    {
        return <<<'BLADE'
            <x-form::checkbox
                name="checkbox_error"
                title="Checkbox"
                switch="false"
                position="left"
                invalidated-title="true"
            />

            <x-form::checkbox
                name="checkbox_switch"
                title="Switch"
                switch="true"
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
            'checkbox_error' => 'The checkbox field is required.',
        ];
    }

    #[Test]
    public function it_hides_the_error_once_checked(): void
    {
        $this->serveWith('form_components.interactive', true);

        $this->browse(function (Browser $browser): void {
            $browser->visit('/')->check('checkbox_error');

            $this->assertValid($browser, 'checkbox_error');
            $this->assertValidTitle($browser, 'checkbox_error');
        });
    }

    #[Test]
    public function it_shows_the_error_again_once_unchecked(): void
    {
        $this->serveWith('form_components.interactive', true);

        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->check('checkbox_error')
                ->uncheck('checkbox_error');

            $this->assertInvalid($browser, 'checkbox_error');
            $this->assertInvalidTitle($browser, 'checkbox_error');
        });
    }

    #[Test]
    public function it_turns_a_switch_on_from_the_keyboard(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')->keys('#checkbox_error', '{tab}');

            $this->assertFocused($browser, '#checkbox_switch');

            $browser->keys('#checkbox_switch', '{space}')->assertChecked('#checkbox_switch');
        });
    }

    #[Test]
    public function it_outlines_a_switch_focused_from_the_keyboard(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/');

            $this->assertSame('none', $this->style($browser, 'label[for="checkbox_switch"] i', 'outline-style'));

            $browser->keys('#checkbox_error', '{tab}');

            $this->assertSame('solid', $this->style($browser, 'label[for="checkbox_switch"] i', 'outline-style'));
        });
    }
}
