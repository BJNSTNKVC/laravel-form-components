<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Tests\Feature\Integration;

use Bjnstnkvc\FormComponents\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class AccessibleFieldsTest extends TestCase
{
    #[Test]
    #[DataProvider('fields')]
    public function it_names_the_field_after_its_title(string $component): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::'.$component.' name="contact" />')
            ->assertSee('for="contact"', false)
            ->assertSee('aria-labelledby="contact-title"', false)
            ->assertSee('id="contact-title"', false);
    }

    #[Test]
    #[DataProvider('fields')]
    public function it_labels_a_field_with_a_custom_id(string $component): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::'.$component.' name="contact" id="primary_contact" />')
            ->assertSee('for="primary_contact"', false)
            ->assertSee('id="primary_contact"', false)
            ->assertSee('aria-labelledby="primary_contact-title"', false)
            ->assertSee('id="primary_contact-title"', false)
            ->assertDontSee('for="contact"', false);
    }

    #[Test]
    #[DataProvider('fields')]
    public function it_marks_an_invalid_field_and_points_it_to_its_error(string $component): void
    {
        $this->withViewErrors(['contact' => 'The contact field is required.'])
            ->blade('<x-form::'.$component.' name="contact" />')
            ->assertSee('aria-invalid="true"', false)
            ->assertSee('aria-describedby="contact-error"', false)
            ->assertSeeInOrder(['id="contact-error"', 'role="alert"', 'The contact field is required.'], false);
    }

    #[Test]
    #[DataProvider('fields')]
    public function it_leaves_a_valid_field_unmarked(string $component): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::'.$component.' name="contact" />')
            ->assertDontSee('aria-invalid', false)
            ->assertDontSee('aria-describedby', false)
            ->assertDontSee('id="contact-error"', false);
    }

    #[Test]
    #[DataProvider('iconFields')]
    public function it_hides_the_icon_from_assistive_technology(string $component): void
    {
        $view = (string) $this->withViewErrors([])
            ->blade('<x-form::'.$component.' name="contact" show-icon="true" icon="/icons/contact.png" />');

        $this->assertMatchesRegularExpression('/aria-hidden="true"\s+class="[^"]*"\s*>\s*<img src="\/icons\/contact.png" alt="">/', $view);
    }

    /**
     * Every component rendering a form field.
     *
     * @return iterable<string, array{string}>
     */
    public static function fields(): iterable
    {
        foreach (['checkbox', 'date', 'date-time', 'email', 'file', 'input', 'password', 'radio', 'search', 'select', 'textarea', 'time'] as $component) {
            yield $component => [$component];
        }
    }

    /**
     * Fields showing a decorative icon beside the value.
     *
     * @return iterable<string, array{string}>
     */
    public static function iconFields(): iterable
    {
        foreach (['date', 'date-time', 'email', 'file', 'input', 'select', 'textarea', 'time'] as $component) {
            yield $component => [$component];
        }
    }
}
