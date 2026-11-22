<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Tests\Feature\Console\Commands;

use Bjnstnkvc\FormComponents\Components\Time\View\Components\Time;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class InstallsComponentsTest extends TestCase
{
    #[Test]
    public function it_registers_every_component_before_alpine_starts(): void
    {
        $this->write('resources/js/app.js', <<<'JS'
            import './bootstrap';

            import Alpine from 'alpinejs';

            window.Alpine = Alpine;

            Alpine.start();

            JS);

        $this->command('form-components:install')
            ->expectsOutput('Registered the form components with Alpine in resources/js/app.js.')
            ->expectsOutput('Run "npm run build" to bundle them.')
            ->doesntExpectOutput('Added Alpine to package.json.')
            ->assertSuccessful();

        $this->assertSame(<<<'JS'
            import './bootstrap';

            import Alpine from 'alpinejs';
            import * as form from '../../../../src/resources/js/index.js';

            window.Alpine = Alpine;

            Alpine.data('form_checkbox', form.checkbox);
            Alpine.data('form_date', form.date);
            Alpine.data('form_date_time', form.dateTime);
            Alpine.data('form_email', form.email);
            Alpine.data('form_file', form.file);
            Alpine.data('form_input', form.input);
            Alpine.data('form_password', form.password);
            Alpine.data('form_radio', form.radio);
            Alpine.data('form_search', form.search);
            Alpine.data('form_select', form.select);
            Alpine.data('form_textarea', form.textarea);
            Alpine.data('form_time', form.time);

            Alpine.start();

            JS, $this->script('resources/js/app.js'));

        $this->assertImportsTheIndex('resources/js/app.js');
        $this->assertFileDoesNotExist($this->root.'/package.json');
    }

    #[Test]
    #[DataProvider('starts')]
    public function it_registers_components_where_the_app_starts_alpine(string $start, string $registration): void
    {
        $this->write('resources/js/app.js', "import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';\n\n".$start."\n");

        $this->command('form-components:install')->assertSuccessful();

        $this->assertStringContainsString($registration, $this->script('resources/js/app.js'));
    }

    #[Test]
    public function it_registers_components_in_the_script_starting_alpine(): void
    {
        $this->write('resources/js/app.js', "import './alpine/setup';\n");
        $this->write('resources/js/alpine/setup.js', "import Alpine from 'alpinejs';\n\nAlpine.start();\n");

        $this->command('form-components:install')
            ->expectsOutput('Registered the form components with Alpine in resources/js/alpine/setup.js.')
            ->assertSuccessful();

        $this->assertSame("import './alpine/setup';\n", $this->script('resources/js/app.js'));
        $this->assertSame(<<<'JS'
            import Alpine from 'alpinejs';
            import * as form from '../../../../../src/resources/js/index.js';

            Alpine.data('form_checkbox', form.checkbox);
            Alpine.data('form_date', form.date);
            Alpine.data('form_date_time', form.dateTime);
            Alpine.data('form_email', form.email);
            Alpine.data('form_file', form.file);
            Alpine.data('form_input', form.input);
            Alpine.data('form_password', form.password);
            Alpine.data('form_radio', form.radio);
            Alpine.data('form_search', form.search);
            Alpine.data('form_select', form.select);
            Alpine.data('form_textarea', form.textarea);
            Alpine.data('form_time', form.time);

            Alpine.start();

            JS, $this->script('resources/js/alpine/setup.js'));

        $this->assertImportsTheIndex('resources/js/alpine/setup.js');
    }

    #[Test]
    public function it_registers_components_once_livewire_brings_alpine(): void
    {
        $this->write('composer.json', '{"require": {"php": "^8.3", "livewire/livewire": "^4.0"}}');
        $this->write('resources/js/app.js', "import './bootstrap';\n");

        $this->command('form-components:install')
            ->expectsOutput('Run "npm run build" to bundle them.')
            ->assertSuccessful();

        $this->assertSame(<<<'JS'
            import './bootstrap';
            import * as form from '../../../../src/resources/js/index.js';

            document.addEventListener('alpine:init', () => {
                window.Alpine.data('form_checkbox', form.checkbox);
                window.Alpine.data('form_date', form.date);
                window.Alpine.data('form_date_time', form.dateTime);
                window.Alpine.data('form_email', form.email);
                window.Alpine.data('form_file', form.file);
                window.Alpine.data('form_input', form.input);
                window.Alpine.data('form_password', form.password);
                window.Alpine.data('form_radio', form.radio);
                window.Alpine.data('form_search', form.search);
                window.Alpine.data('form_select', form.select);
                window.Alpine.data('form_textarea', form.textarea);
                window.Alpine.data('form_time', form.time);
            });

            JS, $this->script('resources/js/app.js'));

        $this->assertImportsTheIndex('resources/js/app.js');
        $this->assertFileDoesNotExist($this->root.'/package.json');
    }

    #[Test]
    public function it_adds_alpine_to_an_app_without_it(): void
    {
        $this->write('composer.json', '{"require": {"php": "^8.3"}}');
        $this->write('resources/js/app.js', "import './bootstrap';\n");
        $this->write('package.json', <<<'JSON'
            {
                "$schema": "https://json.schemastore.org/package.json",
                "private": true,
                "type": "module",
                "scripts": {
                    "build": "vite build",
                    "dev": "vite"
                },
                "devDependencies": {
                    "@tailwindcss/vite": "^4.0.0",
                    "axios": "^1.11.0",
                    "laravel-vite-plugin": "^2.0.0",
                    "tailwindcss": "^4.0.0",
                    "vite": "^7.0.4"
                }
            }

            JSON);

        $this->command('form-components:install')
            ->expectsOutput('Registered the form components with Alpine in resources/js/app.js.')
            ->expectsOutput('Added Alpine to package.json.')
            ->expectsOutput('Run "npm install && npm run build" to bundle them.')
            ->assertSuccessful();

        $this->assertSame(<<<'JS'
            import './bootstrap';
            import Alpine from 'alpinejs';
            import * as form from '../../../../src/resources/js/index.js';

            window.Alpine = Alpine;

            Alpine.data('form_checkbox', form.checkbox);
            Alpine.data('form_date', form.date);
            Alpine.data('form_date_time', form.dateTime);
            Alpine.data('form_email', form.email);
            Alpine.data('form_file', form.file);
            Alpine.data('form_input', form.input);
            Alpine.data('form_password', form.password);
            Alpine.data('form_radio', form.radio);
            Alpine.data('form_search', form.search);
            Alpine.data('form_select', form.select);
            Alpine.data('form_textarea', form.textarea);
            Alpine.data('form_time', form.time);

            Alpine.start();

            JS, $this->script('resources/js/app.js'));

        $this->assertSame(<<<'JSON'
            {
                "$schema": "https://json.schemastore.org/package.json",
                "private": true,
                "type": "module",
                "scripts": {
                    "build": "vite build",
                    "dev": "vite"
                },
                "devDependencies": {
                    "@tailwindcss/vite": "^4.0.0",
                    "alpinejs": "^3.15.0",
                    "axios": "^1.11.0",
                    "laravel-vite-plugin": "^2.0.0",
                    "tailwindcss": "^4.0.0",
                    "vite": "^7.0.4"
                }
            }

            JSON, $this->script('package.json'));
    }

    #[Test]
    public function it_keeps_the_alpine_an_app_already_requires(): void
    {
        $this->write('resources/js/app.js', "import Alpine from 'alpinejs';\n");
        $this->write('package.json', '{"dependencies": {"alpinejs": "^3.0.0"}}');

        $this->command('form-components:install')
            ->expectsOutput('Run "npm run build" to bundle them.')
            ->doesntExpectOutput('Added Alpine to package.json.')
            ->assertSuccessful();

        $this->assertSame(1, substr_count($this->script('resources/js/app.js'), 'import Alpine'));
        $this->assertStringContainsString("Alpine.data('form_time', form.time);\n\nAlpine.start();\n", $this->script('resources/js/app.js'));
        $this->assertSame('{"dependencies": {"alpinejs": "^3.0.0"}}', $this->script('package.json'));
    }

    #[Test]
    public function it_creates_the_app_script_when_missing(): void
    {
        $this->command('form-components:install')
            ->expectsOutput('Registered the form components with Alpine in resources/js/app.js.')
            ->expectsOutput('Added Alpine to package.json.')
            ->assertSuccessful();

        $this->assertStringStartsWith("import Alpine from 'alpinejs';\nimport * as form from '../../../../src/resources/js/index.js';\n\nwindow.Alpine = Alpine;\n\nAlpine.data('form_checkbox', form.checkbox);", $this->script('resources/js/app.js'));
        $this->assertStringEndsWith("Alpine.data('form_time', form.time);\n\nAlpine.start();\n", $this->script('resources/js/app.js'));
        $this->assertImportsTheIndex('resources/js/app.js');
        $this->assertSame("{\n    \"devDependencies\": {\n        \"alpinejs\": \"^3.15.0\"\n    }\n}\n", $this->script('package.json'));
    }

    #[Test]
    public function it_registers_only_the_configured_components(): void
    {
        config()->set('form_components.components', [
            'button' => 'Button',
            'password' => 'Password',
            'date-time' => 'DateTime',
        ]);

        $this->write('resources/js/app.js', "Alpine.start();\n");

        $this->command('form-components:install')->assertSuccessful();

        $this->assertSame(<<<'JS'
            import * as form from '../../../../src/resources/js/index.js';

            Alpine.data('form_date_time', form.dateTime);
            Alpine.data('form_password', form.password);

            Alpine.start();

            JS, $this->script('resources/js/app.js'));
    }

    #[Test]
    public function it_registers_only_the_components_not_registered_yet(): void
    {
        $this->write('resources/js/app.js', <<<'JS'
            import Alpine from 'alpinejs';
            import * as fields from '../../../../src/resources/js/index.js';

            Alpine.data("form_password", fields.password);

            Alpine.start();

            JS);

        $this->command('form-components:install')->assertSuccessful();

        $script = $this->script('resources/js/app.js');

        $this->assertSame(1, substr_count($script, 'import * as'));
        $this->assertSame(1, substr_count($script, 'form_password'));
        $this->assertStringContainsString("Alpine.data('form_time', fields.time);", $script);
    }

    #[Test]
    public function it_registers_the_copied_script_of_a_copied_component(): void
    {
        config()->set('form_components.components', [
            'password' => 'App\View\Components\Form\Password',
            'time' => Time::class,
        ]);

        $this->write('resources/js/app.js', "Alpine.start();\n");
        $this->write('resources/js/components/form/password.js', 'export default () => ({});');

        $this->command('form-components:install')->assertSuccessful();

        $this->assertSame(<<<'JS'
            import formPassword from './components/form/password.js';
            import * as form from '../../../../src/resources/js/index.js';

            Alpine.data('form_password', formPassword);
            Alpine.data('form_time', form.time);

            Alpine.start();

            JS, $this->script('resources/js/app.js'));
    }

    #[Test]
    public function it_registers_the_package_script_of_a_component_copied_without_its_script(): void
    {
        config()->set('form_components.components', ['password' => 'App\View\Components\Form\Password']);

        $this->write('resources/js/app.js', "Alpine.start();\n");

        $this->command('form-components:install')->assertSuccessful();

        $this->assertStringContainsString("Alpine.data('form_password', form.password);", $this->script('resources/js/app.js'));
    }

    #[Test]
    public function it_leaves_an_installed_app_alone(): void
    {
        $this->write('resources/js/app.js', "import Alpine from 'alpinejs';\n\nAlpine.start();\n");

        $this->command('form-components:install')->assertSuccessful();

        $script = $this->script('resources/js/app.js');

        $this->command('form-components:install')
            ->expectsOutput('The form components are already registered with Alpine.')
            ->doesntExpectOutput('Run "npm run build" to bundle them.')
            ->assertSuccessful();

        $this->assertSame($script, $this->script('resources/js/app.js'));
    }

    /**
     * Ways an app starts Alpine, with the registration expected before it.
     *
     * @return array<string, array{string, string}>
     */
    public static function starts(): array
    {
        return [
            'alpine' => [
                'Alpine.start();',
                "Alpine.data('form_time', form.time);\n\nAlpine.start();",
            ],
            'alpine on the window' => [
                'window.Alpine.start();',
                "window.Alpine.data('form_time', form.time);\n\nwindow.Alpine.start();",
            ],
            'livewire' => [
                'Livewire.start();',
                "Alpine.data('form_time', form.time);\n\nLivewire.start();",
            ],
            'livewire on the window' => [
                'window.Livewire.start();',
                "window.Alpine.data('form_time', form.time);\n\nwindow.Livewire.start();",
            ],
            'indented' => [
                "document.addEventListener('DOMContentLoaded', () => {\n    Alpine.start();\n});",
                "    Alpine.data('form_time', form.time);\n\n    Alpine.start();",
            ],
        ];
    }

    /**
     * Assert the script imports the package index as a namespace.
     */
    private function assertImportsTheIndex(string $path): void
    {
        $this->assertSame(1, preg_match("/^import \\* as form from '([^']+)';$/m", $this->script($path), $import));
        $this->assertSame(realpath(dirname(__DIR__, 4).'/src/resources/js/index.js'), realpath(dirname($this->root.'/'.$path).'/'.($import[1] ?? '')));
    }
}
