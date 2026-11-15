<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Tests\Browser;

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
}
