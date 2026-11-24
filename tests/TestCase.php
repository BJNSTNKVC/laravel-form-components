<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Tests;

use Bjnstnkvc\FormComponents\Tests\Concerns\LoadsThePackage;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Orchestra\Testbench\TestCase as Testbench;

abstract class TestCase extends Testbench
{
    use LoadsThePackage;

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

    /**
     * Switch to a locale translating the given package lines.
     *
     * @param  array<string, string>  $lines
     */
    protected function translate(string $locale, array $lines): void
    {
        Lang::addLines($lines, $locale, 'form-components');
        App::setLocale($locale);
    }
}
