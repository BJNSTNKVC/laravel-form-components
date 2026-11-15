<?php

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

    #[Test]
    public function it_styles_the_components(): void
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
    public function it_highlights_invalid_fields(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')->assertSee('The input field is required.');

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
    public function it_opens_the_notch_when_a_floating_title_rises(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/');

            $legend = 'label[for="password_floating_squared"] legend';

            $this->assertSame('0.01px', $this->style($browser, $legend, 'max-width'));

            $browser->type('password_floating_squared', 'secret')->pause(400);

            $this->assertSame('100%', $this->style($browser, $legend, 'max-width'));
        });
    }

    #[Test]
    public function it_floats_the_file_title_once_a_file_is_chosen(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/');

            $legend = 'label[for="file_floating_rounded_s"] legend';

            $this->assertSame('0.01px', $this->style($browser, $legend, 'max-width'));

            $browser->attach('file_floating_rounded_s', __FILE__)->pause(400);

            $this->assertSame('100%', $this->style($browser, $legend, 'max-width'));
            $this->assertSame('PlaygroundTest.php', $browser->value('input[name="file_floating_rounded_s"] ~ .form-group__file-holder'));
        });
    }

    /**
     * Computed value of a CSS property on an element.
     */
    private function style(Browser $browser, string $selector, string $property): string
    {
        $value = $browser->script(sprintf(
            'return getComputedStyle(document.querySelector(%s)).getPropertyValue(%s);',
            json_encode($selector),
            json_encode($property),
        ))[0];

        $this->assertIsString($value);

        return $value;
    }
}
