<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Tests\Browser;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Application;
use Laravel\Dusk\Browser;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\Dusk\Options;
use Orchestra\Testbench\Dusk\TestCase as Testbench;
use Override;

abstract class TestCase extends Testbench
{
    use WithWorkbench;

    /**
     * Talk to Selenium using the W3C WebDriver protocol.
     */
    #[Override]
    public static function setUpBeforeClass(): void
    {
        Options::$w3cCompliant = true;

        parent::setUpBeforeClass();
    }

    /**
     * Serve the playground with a config value changed.
     */
    protected function serveWith(string $key, mixed $value): void
    {
        $this->beforeServingApplication(function (Application $app, Repository $config) use ($key, $value): void {
            $config->set($key, $value);
        });
    }

    /**
     * Computed value of a CSS property on an element.
     */
    protected function style(Browser $browser, string $selector, string $property): string
    {
        $value = $browser->script(sprintf(
            'return getComputedStyle(document.querySelector(%s)).getPropertyValue(%s);',
            json_encode($selector),
            json_encode($property),
        ))[0];

        $this->assertIsString($value);

        return $value;
    }

    /**
     * Assert the field is marked invalid with its error shown.
     */
    protected function assertInvalid(Browser $browser, string $name): void
    {
        $browser->assertAttribute('label[for="'.$name.'"]', 'invalid', '')
            ->assertVisible('label[for="'.$name.'"] .form-group__error');
    }

    /**
     * Assert the field is marked valid with its error hidden.
     */
    protected function assertValid(Browser $browser, string $name): void
    {
        $browser->assertAttributeMissing('label[for="'.$name.'"]', 'invalid')
            ->assertMissing('label[for="'.$name.'"] .form-group__error');
    }

    /**
     * Assert the field title is coloured as invalid.
     */
    protected function assertInvalidTitle(Browser $browser, string $name): void
    {
        $this->assertTrue($this->hasInvalidTitle($browser, $name), 'The ['.$name.'] title is not marked invalid.');
    }

    /**
     * Assert the field title is not coloured as invalid.
     */
    protected function assertValidTitle(Browser $browser, string $name): void
    {
        $this->assertFalse($this->hasInvalidTitle($browser, $name), 'The ['.$name.'] title is marked invalid.');
    }

    /**
     * Determine whether the field title carries the invalid class.
     */
    private function hasInvalidTitle(Browser $browser, string $name): bool
    {
        $invalid = $browser->script(sprintf(
            'return document.querySelector(%s).classList.contains("fc-is-invalid");',
            json_encode('label[for="'.$name.'"] .form-group__title'),
        ))[0];

        $this->assertIsBool($invalid);

        return $invalid;
    }

    /**
     * Assert the floating title of the field has risen.
     */
    protected function assertRisenTitle(Browser $browser, string $name): void
    {
        $this->assertStyleSettles($browser, 'label[for="'.$name.'"] legend', 'max-width', '100%');
    }

    /**
     * Assert the floating title of the field rests inside it.
     */
    protected function assertRestingTitle(Browser $browser, string $name): void
    {
        $this->assertStyleSettles($browser, 'label[for="'.$name.'"] legend', 'max-width', '0.01px');
    }

    /**
     * Assert a CSS property reaches a value once transitions finish.
     */
    protected function assertStyleSettles(Browser $browser, string $selector, string $property, string $value): void
    {
        $browser->waitUntil(sprintf(
            'getComputedStyle(document.querySelector(%s)).getPropertyValue(%s) === %s',
            json_encode($selector),
            json_encode($property),
            json_encode($value),
        ), 2);

        $this->assertSame($value, $this->style($browser, $selector, $property));
    }
}
