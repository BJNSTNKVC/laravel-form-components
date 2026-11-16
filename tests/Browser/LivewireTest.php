<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;

class LivewireTest extends TestCase
{
    #[Test]
    public function it_runs_on_the_alpine_livewire_brings(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/livewire')->waitUntil('window.Livewire && window.Alpine', 2);

            $this->assertTrue($browser->script('return window.Alpine.__fromLivewire === true;')[0]);
        });
    }

    #[Test]
    public function it_toggles_the_password_once_per_click(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/livewire')
                ->type('password', 'secret')
                ->click('label[for="password"] [x-ref="show"]')
                ->assertAttribute('#password', 'type', 'text');
        });
    }

    #[Test]
    public function it_runs_components_livewire_adds_later(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/livewire')
                ->click('#add')
                ->waitFor('#search')
                ->type('search', 'Query')
                ->assertVisible('label[for="search"] [x-ref="clear"]')
                ->click('label[for="search"] [x-ref="clear"]')
                ->assertInputValue('search', '');
        });
    }
}
