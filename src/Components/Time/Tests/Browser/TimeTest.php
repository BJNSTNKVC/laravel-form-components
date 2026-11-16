<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Time\Tests\Browser;

use Bjnstnkvc\FormComponents\Tests\Browser\TestCase;
use Laravel\Dusk\Browser;
use Override;
use PHPUnit\Framework\Attributes\Test;

class TimeTest extends TestCase
{
    /**
     * A time with a floating title, and an invalid time.
     */
    #[Override]
    protected function page(): string
    {
        return <<<'BLADE'
            <x-form::time
                name="time_floating_squared"
                title="Time"
                label-type="floating"
                border="full"
                border-radius="squared"
            />

            <x-form::time
                name="time_error"
                title="Time"
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
            'time_error' => 'The time field is required.',
        ];
    }

    #[Test]
    public function it_raises_the_floating_title_once_a_time_is_entered(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/');

            $this->assertRestingTitle($browser, 'time_floating_squared');

            $browser->keys('#time_floating_squared', '103045AM')
                ->assertInputValue('time_floating_squared', '10:30:45')
                ->assertAttribute('#time_floating_squared', 'value', '10:30:45');

            $this->assertRisenTitle($browser, 'time_floating_squared');
        });
    }

    #[Test]
    public function it_hides_the_error_once_a_time_is_entered(): void
    {
        $this->serveWith('form_components.interactive', true);

        $this->browse(function (Browser $browser): void {
            $browser->visit('/')->keys('#time_error', '103045AM');

            $this->assertValid($browser, 'time_error');
            $this->assertValidTitle($browser, 'time_error');
        });
    }

    #[Test]
    public function it_shows_the_error_again_once_the_time_is_removed(): void
    {
        $this->serveWith('form_components.interactive', true);

        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->keys('#time_error', '103045AM')
                ->keys('#time_error', '{backspace}');

            $this->assertInvalid($browser, 'time_error');
            $this->assertInvalidTitle($browser, 'time_error');
        });
    }
}
