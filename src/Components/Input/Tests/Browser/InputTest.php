<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Input\Tests\Browser;

use Bjnstnkvc\FormComponents\Tests\Browser\TestCase;
use Laravel\Dusk\Browser;
use Override;
use PHPUnit\Framework\Attributes\Test;

class InputTest extends TestCase
{
    /**
     * Inputs with each border, and an invalid input.
     */
    #[Override]
    protected function page(): string
    {
        return <<<'BLADE'
            <x-form::input
                name="input_column_bottom"
                title="Input"
                label-type="column"
                border="bottom"
                border-radius="squared"
            />

            <x-form::input
                name="input_column_squared"
                title="Input"
                label-type="column"
                border="full"
                border-radius="squared"
            />

            <x-form::input
                name="input_column_rounded_s"
                title="Input"
                label-type="column"
                border="full"
                border-radius="rounded-s"
            />

            <x-form::input
                name="input_error"
                title="Input"
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
            'input_error' => 'The input field is required.',
        ];
    }

    #[Test]
    public function it_styles_the_input(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/');

            $this->assertSame('40px', $this->style($browser, 'input[name="input_column_bottom"]', 'height'));
            $this->assertSame('0px', $this->style($browser, 'input[name="input_column_bottom"]', 'border-top-width'));
            $this->assertSame('2px', $this->style($browser, 'input[name="input_column_bottom"]', 'border-bottom-width'));
            $this->assertSame('2px', $this->style($browser, 'input[name="input_column_squared"]', 'border-top-width'));
            $this->assertSame('8px', $this->style($browser, 'input[name="input_column_rounded_s"]', 'border-top-left-radius'));
        });
    }

    #[Test]
    public function it_highlights_an_invalid_input(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')->assertSee('The input field is required.');

            $this->assertInvalid($browser, 'input_error');
            $this->assertInvalidTitle($browser, 'input_error');
            $this->assertSame(
                $this->style($browser, 'label[for="input_error"] [role="alert"]', 'color'),
                $this->style($browser, 'input[name="input_error"]', 'border-bottom-color'),
            );
            $this->assertNotSame(
                $this->style($browser, 'input[name="input_column_bottom"]', 'border-bottom-color'),
                $this->style($browser, 'input[name="input_error"]', 'border-bottom-color'),
            );
        });
    }

    #[Test]
    public function it_keeps_the_error_while_typing_when_not_interactive(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')->type('input_error', 'Value');

            $this->assertInvalid($browser, 'input_error');
            $this->assertInvalidTitle($browser, 'input_error');
        });
    }

    #[Test]
    public function it_hides_the_error_once_a_value_is_typed(): void
    {
        $this->serveWith('form_components.interactive', true);

        $this->browse(function (Browser $browser): void {
            $browser->visit('/')->type('input_error', 'Value');

            $this->assertValid($browser, 'input_error');
            $this->assertValidTitle($browser, 'input_error');
        });
    }

    #[Test]
    public function it_shows_the_error_again_once_the_value_is_removed(): void
    {
        $this->serveWith('form_components.interactive', true);

        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->type('input_error', 'V')
                ->keys('#input_error', '{backspace}');

            $this->assertInvalid($browser, 'input_error');
            $this->assertInvalidTitle($browser, 'input_error');
        });
    }
}
