<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Radio\Tests\Browser;

use Bjnstnkvc\FormComponents\Tests\Browser\TestCase;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;

class RadioTest extends TestCase
{
    #[Test]
    public function it_hides_the_error_once_chosen(): void
    {
        $this->serveWith('form_components.interactive', true);

        $this->browse(function (Browser $browser): void {
            $browser->visit('/')->click('#radio_error');

            $this->assertValid($browser, 'radio_error');
            $this->assertValidTitle($browser, 'radio_error');
        });
    }
}
