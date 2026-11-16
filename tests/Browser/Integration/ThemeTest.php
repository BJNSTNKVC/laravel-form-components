<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Tests\Browser\Integration;

use Bjnstnkvc\FormComponents\Tests\Browser\TestCase;
use Laravel\Dusk\Browser;
use Override;
use PHPUnit\Framework\Attributes\Test;

class ThemeTest extends TestCase
{
    /**
     * Components taking each theme variable.
     */
    #[Override]
    protected function page(): string
    {
        return <<<'BLADE'
            <x-form::base
                action="#"
                id="form-column-rounded-s"
            >
                <x-form::input
                    name="input_column_rounded_s"
                    title="Input"
                    label-type="column"
                    border="full"
                    border-radius="rounded-s"
                />
            </x-form::base>

            <x-form::input
                name="input_column_squared"
                title="Input"
                label-type="column"
                border="full"
                border-radius="squared"
            />

            <x-form::input
                name="input_row_rounded_s"
                title="Input"
                label-type="row"
                border="full"
                border-radius="rounded-s"
            />

            <x-form::input
                name="input_error"
                title="Input"
                invalidated-title="true"
            />

            <x-form::checkbox
                name="checkbox"
                switch="false"
            />

            <x-form::checkbox
                name="checkbox_switch"
                title="Checkbox Switch"
                checked="true"
            />

            <x-form::button name="submit" />
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
    public function it_uses_the_built_in_theme_by_default(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/');

            $this->assertSame('rgb(0, 0, 0)', $this->style($browser, 'button[type="submit"]', 'background-color'));
            $this->assertSame('rgb(255, 255, 255)', $this->style($browser, 'button[type="submit"]', 'color'));
            $this->assertSame('rgb(0, 0, 0)', $this->style($browser, 'label[for="checkbox_switch"] i', 'background-color'));
            $this->assertSame('rgb(0, 0, 0)', $this->style($browser, 'input[name="checkbox"]', 'accent-color'));
            $this->assertSame('rgb(255, 255, 255)', $this->style($browser, 'input[name="input_column_squared"]', 'background-color'));
            $this->assertSame('rgb(0, 0, 0)', $this->style($browser, 'input[name="input_column_squared"]', 'color'));
            $this->assertSame('oklch(0.577 0.245 27.325)', $this->style($browser, 'label[for="input_error"] [role="alert"]', 'color'));
        });
    }

    #[Test]
    public function it_uses_the_page_font(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/');

            $this->assertSame('system-ui, sans-serif', $this->style($browser, 'input[name="input_column_squared"]', 'font-family'));
            $this->assertSame('system-ui, sans-serif', $this->style($browser, 'button[type="submit"]', 'font-family'));
        });
    }

    #[Test]
    public function it_turns_dark_inside_a_dark_class(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')->script('document.documentElement.classList.add("dark");');

            $this->assertDarkTheme($browser);
        });
    }

    #[Test]
    public function it_turns_dark_with_a_dark_colour_scheme(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')->script('document.documentElement.style.colorScheme = "dark";');

            $this->assertDarkTheme($browser);
        });
    }

    #[Test]
    public function it_uses_theme_variables_declared_before_its_stylesheet(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')->script(
                'document.head.insertAdjacentHTML("afterbegin", "<style>:root { --fc-primary: rgb(79, 70, 229); --fc-font: monospace; --fc-radius-small: 2px; --fc-danger: rgb(234, 88, 12); }</style>");',
            );

            $this->assertSame('rgb(79, 70, 229)', $this->style($browser, 'button[type="submit"]', 'background-color'));
            $this->assertStyleSettles($browser, 'label[for="checkbox_switch"] i', 'background-color', 'rgb(79, 70, 229)');
            $this->assertSame('rgb(79, 70, 229)', $this->style($browser, 'input[name="checkbox"]', 'accent-color'));
            $this->assertSame('monospace', $this->style($browser, 'input[name="input_column_squared"]', 'font-family'));
            $this->assertSame('2px', $this->style($browser, 'input[name="input_column_rounded_s"]', 'border-top-left-radius'));
            $this->assertSame('rgb(234, 88, 12)', $this->style($browser, 'label[for="input_error"] [role="alert"]', 'color'));
        });
    }

    #[Test]
    public function it_scopes_theme_variables_to_the_element_declaring_them(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')->script('document.getElementById("form-column-rounded-s").style.setProperty("--fc-radius-small", "2px");');

            $this->assertSame('2px', $this->style($browser, 'input[name="input_column_rounded_s"]', 'border-top-left-radius'));
            $this->assertSame('8px', $this->style($browser, 'input[name="input_row_rounded_s"]', 'border-top-left-radius'));
        });
    }

    /**
     * Assert the components switched to their dark colours.
     */
    private function assertDarkTheme(Browser $browser): void
    {
        $this->assertSame('rgb(255, 255, 255)', $this->style($browser, 'button[type="submit"]', 'background-color'));
        $this->assertSame('rgb(0, 0, 0)', $this->style($browser, 'button[type="submit"]', 'color'));
        $this->assertStyleSettles($browser, 'label[for="checkbox_switch"] i', 'background-color', 'rgb(255, 255, 255)');
        $this->assertNotSame('rgb(255, 255, 255)', $this->style($browser, 'input[name="input_column_squared"]', 'background-color'));
        $this->assertSame('oklch(0.704 0.191 22.216)', $this->style($browser, 'label[for="input_error"] [role="alert"]', 'color'));
    }
}
