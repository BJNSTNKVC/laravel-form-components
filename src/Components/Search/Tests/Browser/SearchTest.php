<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Search\Tests\Browser;

use Bjnstnkvc\FormComponents\Tests\Browser\TestCase;
use Laravel\Dusk\Browser;
use Override;
use PHPUnit\Framework\Attributes\Test;

class SearchTest extends TestCase
{
    /**
     * Selector of the icon that clears the search.
     */
    private const string CLEAR = 'label[for="search_column_bottom"] [x-ref="clear"]';

    /**
     * A search, and an invalid search.
     */
    #[Override]
    protected function page(): string
    {
        return <<<'BLADE'
            <x-form::search
                name="search_column_bottom"
                title="Search"
                label-type="column"
                border="bottom"
                border-radius="squared"
            />

            <x-form::search
                name="search_error"
                title="Search"
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
            'search_error' => 'The search field is required.',
        ];
    }

    #[Test]
    public function it_hides_the_clear_button_while_empty(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')->assertMissing(self::CLEAR);
        });
    }

    #[Test]
    public function it_shows_the_clear_button_once_a_value_is_typed(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->type('search_column_bottom', 'Query')
                ->assertVisible(self::CLEAR);
        });
    }

    #[Test]
    public function it_hides_the_clear_button_once_the_value_is_removed(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->type('search_column_bottom', 'Q')
                ->keys('#search_column_bottom', '{backspace}')
                ->assertMissing(self::CLEAR);
        });
    }

    #[Test]
    public function it_clears_the_search_with_the_clear_button(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->type('search_column_bottom', 'Query')
                ->click(self::CLEAR)
                ->assertInputValue('search_column_bottom', '')
                ->assertMissing(self::CLEAR);
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

    #[Test]
    public function it_shows_the_error_again_once_the_search_is_cleared(): void
    {
        $this->serveWith('form_components.interactive', true);

        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->type('search_error', 'Query')
                ->click('label[for="search_error"] [x-ref="clear"]');

            $this->assertInvalid($browser, 'search_error');
            $this->assertInvalidTitle($browser, 'search_error');
        });
    }
}
