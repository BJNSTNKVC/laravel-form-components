<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Textarea\Tests\Browser;

use Bjnstnkvc\FormComponents\Tests\Browser\TestCase;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class TextareaTest extends TestCase
{
    #[Test]
    public function it_keeps_its_height_when_not_auto_expanding(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/');

            $height = $this->height($browser, 'textarea_column_bottom');

            $browser->type('textarea_column_bottom', $this->lines());

            $this->assertSame($height, $this->height($browser, 'textarea_column_bottom'));
        });
    }

    #[Test]
    #[DataProvider('borders')]
    public function it_grows_to_fit_its_content_when_auto_expanding(string $name): void
    {
        $this->serveWith('form_components.auto_expand', true);

        $this->browse(function (Browser $browser) use ($name): void {
            $browser->visit('/');

            $height = $this->height($browser, $name);

            $browser->type($name, $this->lines());

            $this->assertGreaterThan($height, $this->height($browser, $name));
            $this->assertSame(
                $browser->script(sprintf('return document.getElementById(%s).scrollHeight;', json_encode($name)))[0],
                $browser->script(sprintf('return document.getElementById(%s).clientHeight;', json_encode($name)))[0],
            );
        });
    }

    #[Test]
    public function it_hides_the_error_once_a_value_is_typed(): void
    {
        $this->serveWith('form_components.interactive', true);

        $this->browse(function (Browser $browser): void {
            $browser->visit('/')->type('textarea_error', 'Value');

            $this->assertValid($browser, 'textarea_error');
            $this->assertValidTitle($browser, 'textarea_error');
        });
    }

    #[Test]
    public function it_shows_the_error_again_once_the_value_is_removed(): void
    {
        $this->serveWith('form_components.interactive', true);

        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->type('textarea_error', 'V')
                ->keys('#textarea_error', '{backspace}');

            $this->assertInvalid($browser, 'textarea_error');
            $this->assertInvalidTitle($browser, 'textarea_error');
        });
    }

    /**
     * Textareas with each border style.
     *
     * @return array<string, array{string}>
     */
    public static function borders(): array
    {
        return [
            'bottom border' => ['textarea_column_bottom'],
            'full border' => ['textarea_column_squared'],
        ];
    }

    /**
     * Rendered height of a textarea in pixels.
     */
    private function height(Browser $browser, string $name): int
    {
        $height = $browser->script(sprintf('return document.getElementById(%s).offsetHeight;', json_encode($name)))[0];

        $this->assertIsInt($height);

        return $height;
    }

    /**
     * Text spanning more lines than the textarea shows.
     */
    private function lines(): string
    {
        return implode("\n", ['One', 'Two', 'Three', 'Four', 'Five', 'Six']);
    }
}
