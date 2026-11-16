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
     * An invalid checkbox.
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
}
