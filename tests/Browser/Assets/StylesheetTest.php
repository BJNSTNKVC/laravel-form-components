<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Tests\Browser\Assets;

use Bjnstnkvc\FormComponents\Tests\Browser\TestCase;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;

class StylesheetTest extends TestCase
{
    #[Test]
    public function it_styles_components_without_a_stylesheet_in_the_page(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/');

            $this->assertSame('rgb(0, 0, 0)', $this->style($browser, 'button[type="submit"]', 'background-color'));
        });
    }

    #[Test]
    public function it_styles_components_under_a_strict_content_security_policy(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/csp');

            $this->assertSame('rgb(0, 0, 0)', $this->style($browser, 'button[type="submit"]', 'background-color'));
        });
    }
}
