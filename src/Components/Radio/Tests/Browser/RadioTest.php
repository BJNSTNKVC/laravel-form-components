<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Radio\Tests\Browser;

use Bjnstnkvc\FormComponents\Tests\Browser\TestCase;
use Laravel\Dusk\Browser;
use Override;
use PHPUnit\Framework\Attributes\Test;

class RadioTest extends TestCase
{
    /**
     * An invalid radio and a radio switch.
     */
    #[Override]
    protected function page(): string
    {
        return <<<'BLADE'
            <x-form::radio
                name="radio_error"
                title="Radio"
                switch="false"
                invalidated-title="true"
            />

            <x-form::radio
                name="radio_switch"
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
            'radio_error' => 'The radio field is required.',
        ];
    }

    #[Test]
    public function it_hides_the_error_once_chosen(): void
    {
        $this->serveWith('form_components.interactive', true);

        $this->browse(function (Browser $browser): void {
            $browser->visit('/')->click('#radio_error');

            $this->assertValid($browser, 'radio_error');
            $this->assertValidTitle($browser, 'radio_error');
        });
    }

    #[Test]
    public function it_turns_a_switch_on_from_the_keyboard(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')->keys('#radio_error', '{tab}');

            $this->assertFocused($browser, '#radio_switch');

            $browser->keys('#radio_switch', '{space}')->assertChecked('#radio_switch');
        });
    }

    #[Test]
    public function it_outlines_a_switch_focused_from_the_keyboard(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/');

            $this->assertSame('none', $this->style($browser, 'label[for="radio_switch"] i', 'outline-style'));

            $browser->keys('#radio_error', '{tab}');

            $this->assertSame('solid', $this->style($browser, 'label[for="radio_switch"] i', 'outline-style'));
        });
    }
}
