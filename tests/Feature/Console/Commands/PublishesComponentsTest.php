<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Tests\Feature\Console\Commands;

use PHPUnit\Framework\Attributes\Test;

class PublishesComponentsTest extends TestCase
{
    #[Test]
    public function it_publishes_the_config_when_the_app_has_none(): void
    {
        unlink($this->publisher->configPath);

        $this->command('components:publish', ['component' => ['Input']])
            ->expectsOutput('Published the config to config/form_components.php.')
            ->expectsOutput('Successfully published Input component class!')
            ->assertSuccessful();

        $this->assertStringContainsString('use App\View\Components\Form\Input;', $this->publishedConfig());
        $this->assertStringContainsString('use Bjnstnkvc\FormComponents\Components\Date\View\Components\Date;', $this->publishedConfig());
    }

    #[Test]
    public function it_publishes_every_component(): void
    {
        $this->command('components:publish')
            ->expectsOutput('Successfully published Input component class!')
            ->assertSuccessful();

        /** @var array<string, class-string> $components */
        $components = config('form_components.components');

        foreach ($components as $view => $class) {
            $name = class_basename($class);

            $this->assertFileExists($this->publisher->publishClass($name));
            $this->assertFileExists($this->publisher->publishView($view));
            $this->assertStringContainsString('use App\View\Components\Form\\'.$name.';', $this->publishedConfig());
        }

        $this->assertStringNotContainsString('Bjnstnkvc\FormComponents\Components', $this->publishedConfig());
    }

    #[Test]
    public function it_publishes_only_the_given_components(): void
    {
        $this->command('components:publish', ['component' => ['Input', 'DateTime']])
            ->expectsOutput('Successfully published DateTime component class!')
            ->expectsOutput('Successfully published Input component class!')
            ->assertSuccessful();

        $this->assertFileExists($this->publisher->publishClass('Input'));
        $this->assertFileExists($this->publisher->publishView('date-time'));
        $this->assertFileDoesNotExist($this->publisher->publishClass('Date'));
        $this->assertStringContainsString('use App\View\Components\Form\DateTime;', $this->publishedConfig());
        $this->assertStringContainsString('use Bjnstnkvc\FormComponents\Components\Date\View\Components\Date;', $this->publishedConfig());
    }

    #[Test]
    public function it_points_the_published_class_at_the_published_view(): void
    {
        $this->command('components:publish', ['component' => ['Input']])->assertSuccessful();

        $class = (string) file_get_contents($this->publisher->publishClass('Input'));

        $this->assertStringContainsString('namespace App\View\Components\Form;', $class);
        $this->assertStringContainsString("view('components.form.input')", $class);
        $this->assertFileEquals($this->publisher->getView('input'), $this->publisher->publishView('input'));
    }

    #[Test]
    public function it_copies_the_script_of_a_component(): void
    {
        $this->command('components:publish', ['component' => ['Password', 'Button']])->assertSuccessful();

        $this->assertFileEquals($this->publisher->getScript('password'), $this->publisher->publishScript('password'));
        $this->assertFileDoesNotExist($this->publisher->publishScript('button'));
    }

    #[Test]
    public function it_registers_the_copied_scripts_with_alpine(): void
    {
        $this->write('resources/js/app.js', $this->installedScript());

        $this->command('components:publish', ['component' => ['Password', 'DateTime']])
            ->expectsOutput('Registered the copied scripts with Alpine in resources/js/app.js.')
            ->expectsOutput('Run "npm run build" to bundle them.')
            ->assertSuccessful();

        $this->assertSame(<<<'JS'
            import Alpine from 'alpinejs';
            import * as form from '../../../../src/resources/js/index.js';
            import formDateTime from './components/form/date-time.js';
            import formPassword from './components/form/password.js';

            window.Alpine = Alpine;

            Alpine.data('form_date_time', formDateTime);
            Alpine.data('form_password', formPassword);
            Alpine.data('form_time', form.time);

            Alpine.start();

            JS, $this->script('resources/js/app.js'));
    }

    #[Test]
    public function it_leaves_an_app_without_registrations_to_the_install_command(): void
    {
        $this->write('resources/js/app.js', "import './bootstrap';\n");

        $this->command('components:publish', ['component' => ['Password']])
            ->doesntExpectOutput('Registered the copied scripts with Alpine in resources/js/app.js.')
            ->assertSuccessful();

        $this->assertSame("import './bootstrap';\n", $this->script('resources/js/app.js'));
    }

    #[Test]
    public function it_keeps_an_already_published_component(): void
    {
        $this->command('components:publish', ['component' => ['Input']])->assertSuccessful();

        file_put_contents($this->publisher->publishClass('Input'), 'customised');

        $this->command('components:publish', ['component' => ['Input']])
            ->expectsOutput('File Input.php already exists!')
            ->doesntExpectOutput('Successfully published Input component class!')
            ->assertSuccessful();

        $this->assertStringEqualsFile($this->publisher->publishClass('Input'), 'customised');
    }

    #[Test]
    public function it_overwrites_an_already_published_component_when_forced(): void
    {
        $this->command('components:publish', ['component' => ['Input']])->assertSuccessful();

        file_put_contents($this->publisher->publishClass('Input'), 'customised');

        $this->command('components:publish', ['component' => ['Input'], '--force' => true])
            ->expectsOutput('Successfully published Input component class!')
            ->assertSuccessful();

        $this->assertStringContainsString('namespace App\View\Components\Form;', (string) file_get_contents($this->publisher->publishClass('Input')));
    }

    #[Test]
    public function it_registers_a_copied_script_once_when_forced(): void
    {
        $this->write('resources/js/app.js', $this->installedScript());

        $this->command('components:publish', ['component' => ['Password']])->assertSuccessful();

        $script = $this->script('resources/js/app.js');

        $this->command('components:publish', ['component' => ['Password'], '--force' => true])
            ->doesntExpectOutput('Registered the copied scripts with Alpine in resources/js/app.js.')
            ->assertSuccessful();

        $this->assertSame($script, $this->script('resources/js/app.js'));
    }
}
