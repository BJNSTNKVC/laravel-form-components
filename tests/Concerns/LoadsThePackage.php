<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Tests\Concerns;

use Bjnstnkvc\FormComponents\FormComponentsServiceProvider;
use Illuminate\Foundation\Application;
use Override;

trait LoadsThePackage
{
    /**
     * Load the package with the config file it ships.
     *
     * @param  Application  $app
     */
    #[Override]
    protected function defineEnvironment($app): void
    {
        $app->make('config')->set('form_components', require dirname(__DIR__, 2).'/src/config/form_components.php');

        $app->register(FormComponentsServiceProvider::class);
    }
}
