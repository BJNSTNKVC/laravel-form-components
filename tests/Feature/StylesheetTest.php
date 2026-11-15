<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Tests\Feature;

use Bjnstnkvc\FormComponents\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class StylesheetTest extends TestCase
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
    public function it_ships_a_minified_copy(): void
    {
        $minified = (string) file_get_contents(__DIR__.'/../../src/public/css/form-components.min.css');

        $this->assertStringNotContainsString("\n  ", $minified);
        $this->assertStringContainsString('.fc\:grid{', $minified);
    }

    /**
     * The compiled, readable stylesheet.
     */
    private function stylesheet(): string
    {
        return (string) file_get_contents(__DIR__.'/../../src/public/css/form-components.css');
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
