<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Tests;

use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as Testbench;

abstract class TestCase extends Testbench
{
    use WithWorkbench;

    /**
     * Path to the SVG icon fixture.
     */
    protected function iconFile(): string
    {
        return __DIR__.'/Fixtures/icon.svg';
    }

    /**
     * Markup of the SVG icon fixture.
     */
    protected function iconMarkup(): string
    {
        return trim((string) file_get_contents($this->iconFile()));
    }
}
