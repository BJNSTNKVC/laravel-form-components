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
        $this->assertFileDoesNotExist($this->publisher->publishScript('input'));
        $this->assertFileEquals($this->packageConfigPath(), $this->publisher->configPath);
    }

    #[Test]
    public function it_registers_the_package_scripts_with_alpine_again(): void
    {
        $this->write('resources/js/app.js', $this->installedScript());

        $this->command('components:publish', ['component' => ['Password', 'DateTime']])->assertSuccessful();

        $this->command('components:restore', ['component' => ['Password', 'DateTime'], '--delete' => true])
            ->expectsOutput('Registered the package scripts with Alpine in resources/js/app.js.')
            ->expectsOutput('Run "npm run build" to bundle them.')
            ->assertSuccessful();

        $this->assertSame($this->installedScript(), $this->script('resources/js/app.js'));
    }

    #[Test]
    public function it_imports_the_package_scripts_when_the_app_registers_only_copies(): void
    {
        $this->write('resources/js/app.js', <<<'JS'
            import Alpine from 'alpinejs';
            import formPassword from './components/form/password.js';

            Alpine.data('form_password', formPassword);

            Alpine.start();

            JS);

        $this->command('components:publish', ['component' => ['Password']])->assertSuccessful();
        $this->command('components:restore', ['component' => ['Password']])->assertSuccessful();

        $this->assertSame(<<<'JS'
            import Alpine from 'alpinejs';
            import * as form from '../../../../src/resources/js/index.js';

            Alpine.data('form_password', form.password);

            Alpine.start();

            JS, $this->script('resources/js/app.js'));
        $this->assertFileExists($this->publisher->publishScript('password'));
    }

    #[Test]
    public function it_keeps_the_import_of_a_copied_script_the_app_still_uses(): void
    {
        $this->write('resources/js/app.js', <<<'JS'
            import Alpine from 'alpinejs';
            import formPassword from './components/form/password.js';

            Alpine.data('form_password', formPassword);
            Alpine.data('secret', formPassword);

            Alpine.start();

            JS);

        $this->command('components:publish', ['component' => ['Password']])->assertSuccessful();
        $this->command('components:restore', ['component' => ['Password']])->assertSuccessful();

        $this->assertStringContainsString("import formPassword from './components/form/password.js';", $this->script('resources/js/app.js'));
        $this->assertStringContainsString("Alpine.data('form_password', form.password);", $this->script('resources/js/app.js'));
    }

    #[Test]
    public function it_restores_nothing_when_nothing_was_published(): void
    {
        $this->command('components:restore')
            ->doesntExpectOutput('Successfully restored Input component class!')
            ->assertSuccessful();

        $this->assertFileEquals($this->packageConfigPath(), $this->publisher->configPath);
    }
}
