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

        $this->root = sys_get_temp_dir().'/form-components-'.uniqid();

        $this->publisher = new Publisher;
        $this->publisher->configPath = $this->root.'/config/form_components.php';
        $this->publisher->classPath = $this->root.'/app/View/Components/Form/';
        $this->publisher->viewPath = $this->root.'/resources/views/components/form/';

        $filesystem = new Filesystem;
        $filesystem->ensureDirectoryExists(dirname($this->publisher->configPath));
        $filesystem->copy($this->packageConfigPath(), $this->publisher->configPath);

        $this->instance(Publisher::class, $this->publisher);
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
     * Contents of the temporary application's config.
     */
    protected function publishedConfig(): string
    {
        return (string) file_get_contents($this->publisher->configPath);
    }
}
