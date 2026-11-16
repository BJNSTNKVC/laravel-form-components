<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Email\Tests\Browser;

use Bjnstnkvc\FormComponents\Tests\Browser\TestCase;
use Laravel\Dusk\Browser;
use Override;
use PHPUnit\Framework\Attributes\Test;

class EmailTest extends TestCase
{
    /**
     * An invalid email without an invalidated title.
     */
    #[Override]
    protected function page(): string
    {
        return <<<'BLADE'
            <x-form::email
                name="email_error"
                title="Email"
                border="full"
                label-type="row"
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
            'email_error' => 'The email field is required.',
        ];
    }

    #[Test]
    public function it_hides_the_error_once_an_email_is_typed(): void
    {
        $this->serveWith('form_components.interactive', true);

        $this->browse(function (Browser $browser): void {
            $browser->visit('/')->type('email_error', 'john@example.com');

            $this->assertValid($browser, 'email_error');
        });
    }

    #[Test]
    public function it_shows_the_error_again_once_the_email_is_removed(): void
    {
        $this->serveWith('form_components.interactive', true);

        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->type('email_error', 'j')
                ->keys('#email_error', '{backspace}');

            $this->assertInvalid($browser, 'email_error');
        });
    }

    #[Test]
    public function it_leaves_the_title_alone_without_an_invalidated_title(): void
    {
        $this->serveWith('form_components.interactive', true);

        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->type('email_error', 'j')
                ->keys('#email_error', '{backspace}');

            $this->assertValidTitle($browser, 'email_error');
        });
    }
}
