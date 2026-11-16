<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Checkbox\Tests\Feature;

use Bjnstnkvc\FormComponents\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class CheckboxTest extends TestCase
{
    #[Test]
    public function it_renders_a_checkbox(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::checkbox name="remember" />')
            ->assertSee('type="checkbox"', false)
            ->assertSee('name="remember"', false);
    }

    #[Test]
    public function it_runs_on_alpine(): void
    {
        $this->withViewErrors(['remember' => 'The remember field is required.'])
            ->blade('<x-form::checkbox name="remember" />')
            ->assertSee('x-data="form_checkbox"', false)
            ->assertSee('x-ref="input"', false)
            ->assertSee('x-ref="error"', false);
    }

    #[Test]
    public function it_is_centred_by_default(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::checkbox name="remember" />')
            ->assertSee('fc:mx-auto')
            ->assertDontSee('fc:mr-auto');
    }

    #[Test]
    public function it_can_be_aligned_to_the_left(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::checkbox name="remember" position="left" />')
            ->assertSee('fc:mr-auto')
            ->assertDontSee('fc:mx-auto');
    }

    #[Test]
    public function it_leaves_the_title_colour_alone_by_default(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::checkbox name="remember" />')
            ->assertDontSee('fc:group-[[invalid]]:text-danger', false);
    }

    #[Test]
    public function it_colours_the_title_of_an_invalid_field(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::checkbox name="remember" invalidated-title="true" />')
            ->assertSee('fc:group-[[invalid]]:text-danger', false);
    }

    #[Test]
    public function it_keeps_errors_while_typing_by_default(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::checkbox name="remember" />')
            ->assertDontSee('data-interactive', false);
    }

    #[Test]
    #[DataProvider('interactiveSwitches')]
    public function it_hides_errors_while_typing_when_interactive(string $attributes, bool $config): void
    {
        config(['form_components.interactive' => $config]);

        $this->withViewErrors([])
            ->blade('<x-form::checkbox name="remember" '.$attributes.' />')
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
            ->blade('<x-form::checkbox name="remember" />')
            ->assertSee('fc:hidden')
            ->assertSee('<i class="fc:[grid-area:input]', false);
    }

    #[Test]
    public function it_renders_a_plain_checkbox_when_the_switch_is_off(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::checkbox name="remember" switch="false" />')
            ->assertSee('fc:size-4')
            ->assertDontSee('<i ', false);
    }
}
