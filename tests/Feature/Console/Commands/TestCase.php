<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Tests\Feature\Console\Commands;

use Bjnstnkvc\FormComponents\Helpers\Publisher;
use Bjnstnkvc\FormComponents\Tests\TestCase as BaseTestCase;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Testing\PendingCommand;
use Override;

abstract class TestCase extends BaseTestCase
{
    /**
     * Temporary application the components are published into.
     */
    protected string $root;

    /**
     * Publisher pointed at the temporary application.
     */
    protected Publisher $publisher;

    /**
     * Publish into a temporary application holding the package config.
     */
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->root = $this->temporaryDirectory().'/form-components-'.uniqid();

        $this->publisher = new Publisher;
        $this->publisher->configPath = $this->root.'/config/form_components.php';
        $this->publisher->classPath = $this->root.'/app/View/Components/Form/';
        $this->publisher->viewPath = $this->root.'/resources/views/components/form/';
        $this->publisher->scriptPath = $this->root.'/resources/js/components/form/';

        $filesystem = new Filesystem;
        $filesystem->ensureDirectoryExists(dirname($this->publisher->configPath));
        $filesystem->copy($this->packageConfigPath(), $this->publisher->configPath);

        $this->instance(Publisher::class, $this->publisher);
        $this->app?->setBasePath($this->root);
    }

    /**
     * Remove the temporary application.
     */
    #[Override]
    protected function tearDown(): void
    {
        (new Filesystem)->deleteDirectory($this->root);

        parent::tearDown();
    }

    /**
     * Keep the application beside the package, as apps keep vendor.
     */
    protected function temporaryDirectory(): string
    {
        return dirname(__DIR__, 4).'/build';
    }

    /**
     * Run an Artisan command with its output mocked.
     *
     * @param  array<string, mixed>  $parameters
     */
    protected function command(string $command, array $parameters = []): PendingCommand
    {
        $pending = $this->artisan($command, $parameters);

        $this->assertInstanceOf(PendingCommand::class, $pending);

        return $pending;
    }

    /**
     * Path to the config shipped with the package.
     */
    protected function packageConfigPath(): string
    {
        return dirname(__DIR__, 4).'/src/config/form_components.php';
    }

    /**
     * Write a file into the temporary application.
     */
    protected function write(string $path, string $content): void
    {
        $filesystem = new Filesystem;
        $filesystem->ensureDirectoryExists(dirname($this->root.'/'.$path));
        $filesystem->put($this->root.'/'.$path, $content);
    }

    /**
     * Contents of a file in the temporary application.
     */
    protected function script(string $path): string
    {
        return (string) file_get_contents($this->root.'/'.$path);
    }

    /**
     * App script registering components the install command's way.
     */
    protected function installedScript(): string
    {
        return <<<'JS'
            import Alpine from 'alpinejs';
            import * as form from '../../../../src/resources/js/index.js';

            window.Alpine = Alpine;

            Alpine.data('form_date_time', form.dateTime);
            Alpine.data('form_password', form.password);
            Alpine.data('form_time', form.time);

            Alpine.start();

            JS;
    }

    /**
     * Contents of the temporary application's config.
     */
    protected function publishedConfig(): string
    {
        return (string) file_get_contents($this->publisher->configPath);
    }
}
