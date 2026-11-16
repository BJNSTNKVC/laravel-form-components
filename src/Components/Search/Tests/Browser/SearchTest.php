<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Search\Tests\Browser;

use Bjnstnkvc\FormComponents\Tests\Browser\TestCase;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;

class SearchTest extends TestCase
{
    #[Test]
    public function it_hides_the_clear_button_while_empty(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')->assertMissing('label[for="search_column_bottom"] .form-group__icon--clear');
        });
    }

    #[Test]
    public function it_shows_the_clear_button_once_a_value_is_typed(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->type('search_column_bottom', 'Query')
                ->assertVisible('label[for="search_column_bottom"] .form-group__icon--clear');
        });
    }

    #[Test]
    public function it_hides_the_clear_button_once_the_value_is_removed(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->type('search_column_bottom', 'Q')
                ->keys('#search_column_bottom', '{backspace}')
                ->assertMissing('label[for="search_column_bottom"] .form-group__icon--clear');
        });
    }

    #[Test]
    public function it_clears_the_search_with_the_clear_button(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->type('search_column_bottom', 'Query')
                ->click('label[for="search_column_bottom"] .form-group__icon--clear')
                ->assertInputValue('search_column_bottom', '')
                ->assertAttribute('#search_column_bottom', 'value', '')
                ->assertMissing('label[for="search_column_bottom"] .form-group__icon--clear');
        });
    }

    #[Test]
    public function it_hides_the_error_once_a_search_is_typed(): void
    {
        $this->serveWith('form_components.interactive', true);

        $this->browse(function (Browser $browser): void {
            $browser->visit('/')->type('search_error', 'Query');

            $this->assertValid($browser, 'search_error');
            $this->assertValidTitle($browser, 'search_error');
        });
    }

    #[Test]
    public function it_shows_the_error_again_once_the_search_is_removed(): void
    {
        $this->serveWith('form_components.interactive', true);

        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->type('search_error', 'Q')
                ->keys('#search_error', '{backspace}');

            $this->assertInvalid($browser, 'search_error');
            $this->assertInvalidTitle($browser, 'search_error');
        });
    }
}
