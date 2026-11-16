<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Tests\Feature\Console\Commands;

use PHPUnit\Framework\Attributes\Test;

class RestoresComponentsTest extends TestCase
{
    #[Test]
    public function it_restores_every_published_component(): void
    {
        $this->command('components:publish', ['component' => ['Input', 'Password']])->assertSuccessful();

        $this->command('components:restore')
            ->expectsOutput('Successfully restored Input component class!')
            ->expectsOutput('Successfully restored Password component class!')
            ->assertSuccessful();

        $this->assertFileEquals($this->packageConfigPath(), $this->publisher->configPath);
        $this->assertFileExists($this->publisher->publishClass('Input'));
        $this->assertFileExists($this->publisher->publishView('password'));
    }

    #[Test]
    public function it_restores_only_the_given_components(): void
    {
        $this->command('components:publish', ['component' => ['Date', 'DateTime']])->assertSuccessful();

        $this->command('components:restore', ['component' => ['Date']])
            ->expectsOutput('Successfully restored Date component class!')
            ->doesntExpectOutput('Successfully restored DateTime component class!')
            ->assertSuccessful();

        $this->assertStringContainsString('use Bjnstnkvc\FormComponents\Components\Date\View\Components\Date;', $this->publishedConfig());
        $this->assertStringContainsString('use App\View\Components\Form\DateTime;', $this->publishedConfig());
    }

    #[Test]
    public function it_deletes_the_published_files_when_asked(): void
    {
        $this->command('components:publish', ['component' => ['Input']])->assertSuccessful();

        $this->command('components:restore', ['component' => ['Input'], '--delete' => true])
            ->expectsOutput('Successfully restored Input component class!')
            ->assertSuccessful();

        $this->assertFileDoesNotExist($this->publisher->publishClass('Input'));
        $this->assertFileDoesNotExist($this->publisher->publishView('input'));
        $this->assertFileEquals($this->packageConfigPath(), $this->publisher->configPath);
    }
}
