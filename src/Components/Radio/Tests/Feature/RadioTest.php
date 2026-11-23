<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Radio\Tests\Feature;

use Bjnstnkvc\FormComponents\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class RadioTest extends TestCase
{
    #[Test]
    public function it_renders_a_radio(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::radio name="plan" />')
            ->assertSee('type="radio"', false)
            ->assertSee('name="plan"', false);
    }

    #[Test]
    public function it_runs_on_alpine(): void
    {
        $this->withViewErrors(['plan' => 'The plan field is required.'])
            ->blade('<x-form::radio name="plan" />')
            ->assertSee('x-data="form_radio"', false)
            ->assertSee('x-ref="input"', false)
            ->assertSee('x-ref="error"', false);
    }

    #[Test]
    public function it_is_centred_by_default(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::radio name="plan" />')
            ->assertSee('fc:mx-auto')
            ->assertDontSee('fc:mr-auto');
    }

    #[Test]
    public function it_can_be_aligned_to_the_left(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::radio name="plan" position="left" />')
            ->assertSee('fc:mr-auto')
            ->assertDontSee('fc:mx-auto');
    }

    #[Test]
    public function it_leaves_the_title_colour_alone_by_default(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::radio name="plan" />')
            ->assertDontSee('fc:group-[[invalid]]:text-danger', false);
    }

    #[Test]
    public function it_colours_the_title_of_an_invalid_field(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::radio name="plan" invalidated-title="true" />')
            ->assertSee('fc:group-[[invalid]]:text-danger', false);
    }

    #[Test]
    public function it_keeps_errors_while_typing_by_default(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::radio name="plan" />')
            ->assertDontSee('data-interactive', false);
    }

    #[Test]
    #[DataProvider('interactiveSwitches')]
    public function it_hides_errors_while_typing_when_interactive(string $attributes, bool $config): void
    {
        config(['form_components.interactive' => $config]);

        $this->withViewErrors([])
            ->blade('<x-form::radio name="plan" '.$attributes.' />')
            ->assertSee('data-interactive', false);
    }

    /**
     * Ways of making errors disappear on input.
     *
     * @return iterable<string, array{string, bool}>
     */
    public static function interactiveSwitches(): iterable
    {
        yield 'attribute' => ['interactive="true"', false];
        yield 'config' => ['', true];
    }

    #[Test]
    public function it_renders_a_switch_by_default(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::radio name="plan" />')
            ->assertSee('fc:sr-only')
            ->assertDontSee('role="', false)
            ->assertSee('></i>', false);
    }

    #[Test]
    public function it_hides_the_drawn_switch_from_assistive_technology(): void
    {
        $view = (string) $this->withViewErrors([])->blade('<x-form::radio name="plan" />');

        $this->assertMatchesRegularExpression('/<i\s+class="[^"]*"\s+aria-hidden="true"\s*><\/i>/', $view);
    }

    #[Test]
    public function it_renders_a_plain_radio_when_the_switch_is_off(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::radio name="plan" switch="false" />')
            ->assertSee('fc:size-4')
            ->assertDontSee('role="', false)
            ->assertDontSee('></i>', false);
    }
}
