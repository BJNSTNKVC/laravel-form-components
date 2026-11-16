<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Tests\Feature\Integration;

use Bjnstnkvc\FormComponents\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class CompiledStylesheetTest extends TestCase
{
    #[Test]
    public function it_only_styles_prefixed_utility_classes(): void
    {
        $selectors = $this->selectors();

        $this->assertNotEmpty($selectors);

        foreach ($selectors as $selector) {
            $this->assertStringContainsString('.fc\:', $selector, sprintf('Selector [%s] is not a prefixed utility.', $selector));
        }
    }

    #[Test]
    public function it_keeps_theme_variables_in_its_own_namespace(): void
    {
        preg_match('/:root, :host \{(.*?)\}/s', $this->stylesheet(), $theme);
        preg_match_all('/^\s*(--[\w-]+):/m', $theme[1] ?? '', $variables);

        $this->assertNotEmpty($variables[1]);

        foreach ($variables[1] as $variable) {
            $this->assertStringStartsWith('--fc-', $variable);
        }
    }

    #[Test]
    public function it_leaves_utilities_outside_cascade_layers(): void
    {
        $this->assertDoesNotMatchRegularExpression('/@layer (theme|base|components|utilities)\b/', $this->stylesheet());
    }

    #[Test]
    #[DataProvider('themeVariables')]
    public function it_reads_a_theme_variable_with_a_default(string $variable, string $default): void
    {
        $this->assertStringContainsString(sprintf('var(%s, %s)', $variable, $default), $this->stylesheet());
    }

    #[Test]
    #[DataProvider('themeVariableNames')]
    public function it_leaves_theme_variables_for_the_app_to_declare(string $variable): void
    {
        $this->assertDoesNotMatchRegularExpression('/^\s*'.preg_quote($variable, '/').':/m', $this->stylesheet());
    }

    #[Test]
    public function it_turns_dark_inside_a_dark_class(): void
    {
        $this->assertMatchesRegularExpression('/\.fc\\\\:dark\\\\:scheme-dark:where\(\.dark, \.dark \*\) \{\s*color-scheme: dark;/', $this->stylesheet());
    }

    #[Test]
    public function it_ships_a_minified_copy(): void
    {
        $minified = (string) file_get_contents(__DIR__.'/../../../src/public/css/form-components.min.css');

        $this->assertStringNotContainsString("\n  ", $minified);
        $this->assertStringContainsString('.fc\:grid{', $minified);
    }

    /**
     * Theme variables an app may set, with their defaults.
     *
     * @return iterable<string, array{string, string}>
     */
    public static function themeVariables(): iterable
    {
        yield 'primary' => ['--fc-primary', 'light-dark(#000, #fff)'];
        yield 'primary foreground' => ['--fc-primary-foreground', 'light-dark(#fff, #000)'];
        yield 'field' => ['--fc-field', 'Field'];
        yield 'field foreground' => ['--fc-field-foreground', 'FieldText'];
        yield 'danger' => ['--fc-danger', 'light-dark(oklch(57.7% 0.245 27.325), oklch(70.4% 0.191 22.216))'];
        yield 'muted' => ['--fc-muted', 'oklch(55.3% 0.013 58.071)'];
        yield 'font' => ['--fc-font', 'inherit'];
        yield 'small radius' => ['--fc-radius-small', '0.5rem'];
        yield 'medium radius' => ['--fc-radius-medium', '0.75rem'];
        yield 'pill radius' => ['--fc-radius-pill', '9999px'];
    }

    /**
     * Theme variables an app may set.
     *
     * @return iterable<string, array{string}>
     */
    public static function themeVariableNames(): iterable
    {
        foreach (self::themeVariables() as $name => [$variable]) {
            yield $name => [$variable];
        }
    }

    /**
     * The compiled, readable stylesheet.
     */
    private function stylesheet(): string
    {
        return (string) file_get_contents(__DIR__.'/../../../src/public/css/form-components.css');
    }

    /**
     * Rule selectors, excluding root variables and property fallbacks.
     *
     * @return list<string>
     */
    private function selectors(): array
    {
        preg_match_all('/^\s*([^@\s{}][^{};\n]*?)\s*\{\s*$/m', $this->stylesheet(), $matches);

        return array_values(array_filter(
            $matches[1],
            fn (string $selector): bool => ! in_array($selector, [':root, :host', '*, ::before, ::after, ::backdrop'], true),
        ));
    }
}
