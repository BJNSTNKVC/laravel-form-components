<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Tests\Browser;

use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;

class ScriptTest extends TestCase
{
    #[Test]
    public function it_runs_components_added_after_the_page_loaded(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->script('document.body.append(document.getElementById("later").content.cloneNode(true));');

            $browser->waitFor('#password_later')
                ->type('password_later', 'secret')
                ->click('label[for="password_later"] [x-ref="show"]')
                ->assertAttribute('#password_later', 'type', 'text');
        });
    }
}
