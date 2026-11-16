<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Tests\Browser;

use Laravel\Dusk\Browser;
use Override;
use PHPUnit\Framework\Attributes\Test;

class ScriptTest extends TestCase
{
    /**
     * A password kept aside to be added later.
     */
    #[Override]
    protected function page(): string
    {
        return <<<'BLADE'
            <template id="later">
                <x-form::password
                    name="password_later"
                    title="Password Added Later"
                />
            </template>
            BLADE;
    }

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
