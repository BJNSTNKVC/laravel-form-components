<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\DateTime\Tests\Browser;

use Bjnstnkvc\FormComponents\Tests\Browser\TestCase;
use Laravel\Dusk\Browser;
use Override;
use PHPUnit\Framework\Attributes\Test;

class DateTimeTest extends TestCase
{
    /**
     * A date and time with a floating title, one invalid.
     */
    #[Override]
    protected function page(): string
    {
        return <<<'BLADE'
            <x-form::date-time
                name="date_time_floating_squared"
                title="Date &amp; Time"
                label-type="floating"
                border="full"
                border-radius="squared"
            />

            <x-form::date-time
                name="date_time_error"
                title="Date &amp; Time"
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
            'date_time_error' => 'The date_time field is required.',
        ];
    }

    #[Test]
    public function it_raises_the_floating_title_once_a_date_and_time_is_entered(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/');

            $this->assertRestingTitle($browser, 'date_time_floating_squared');

            $browser->keys('#date_time_floating_squared', '02152026', '{arrow_right}', '103045AM')
                ->assertInputValue('date_time_floating_squared', '2026-02-15T10:30:45')
                ->assertAttribute('#date_time_floating_squared', 'value', '2026-02-15T10:30:45');

            $this->assertRisenTitle($browser, 'date_time_floating_squared');
        });
    }

    #[Test]
    public function it_hides_the_error_once_a_date_and_time_is_entered(): void
    {
        $this->serveWith('form_components.interactive', true);

        $this->browse(function (Browser $browser): void {
            $browser->visit('/')->keys('#date_time_error', '02152026', '{arrow_right}', '103045AM');

            $this->assertValid($browser, 'date_time_error');
            $this->assertValidTitle($browser, 'date_time_error');
        });
    }

    #[Test]
    public function it_shows_the_error_again_once_the_date_and_time_is_removed(): void
    {
        $this->serveWith('form_components.interactive', true);

        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->keys('#date_time_error', '02152026', '{arrow_right}', '103045AM')
                ->keys('#date_time_error', '{backspace}');

            $this->assertInvalid($browser, 'date_time_error');
            $this->assertInvalidTitle($browser, 'date_time_error');
        });
    }
}
