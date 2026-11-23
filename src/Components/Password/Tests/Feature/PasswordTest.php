<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Password\Tests\Feature;

use Bjnstnkvc\FormComponents\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class PasswordTest extends TestCase
{
    #[Test]
    public function it_renders_a_password_input(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::password name="password" />')
            ->assertSee('type="password"', false)
            ->assertSee('name="password"', false);
    }

    #[Test]
    public function it_runs_on_alpine(): void
    {
        $this->withViewErrors(['password' => 'The password field is required.'])
            ->blade('<x-form::password name="password" />')
            ->assertSee('x-data="form_password"', false)
            ->assertSee('x-ref="input"', false)
            ->assertSee('x-ref="error"', false);
    }

    #[Test]
    public function it_hides_the_placeholder_behind_a_floating_title(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::password name="password" title="Password" label-type="floating" />')
            ->assertSee('fc:focus:placeholder:text-transparent');
    }

    #[Test]
    public function it_renders_a_bottom_border_by_default(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::password name="password" title="Password" />')
            ->assertSee('fc:border-b-2')
            ->assertDontSee('fc:border-2');
    }

    #[Test]
    public function it_notches_the_border_for_a_floating_title(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::password name="password" title="Password" label-type="floating" border="full" />')
            ->assertSee('<fieldset', false)
            ->assertSee('aria-hidden="true"', false)
            ->assertSee('<legend><span class="fc:px-2">Password</span></legend>', false);
    }

    #[Test]
    #[DataProvider('unnotchedBorders')]
    public function it_does_not_notch_a_border_without_a_floating_title(string $attributes): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::password name="password" title="Password" '.$attributes.' />')
            ->assertDontSee('<fieldset', false);
    }

    /**
     * Attributes that render a border without a notch.
     *
     * @return iterable<string, array{string}>
     */
    public static function unnotchedBorders(): iterable
    {
        yield 'full border only' => ['border="full"'];
        yield 'floating title only' => ['label-type="floating"'];
    }

    #[Test]
    public function it_renders_a_full_rounded_border(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::password name="password" title="Password" border="full" border-radius="rounded" />')
            ->assertSee('fc:border-2')
            ->assertSee('fc:rounded-pill')
            ->assertDontSee('fc:border-b-2');
    }

    #[Test]
    public function it_leaves_the_title_colour_alone_by_default(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::password name="password" />')
            ->assertDontSee('fc:group-[[invalid]]:text-danger', false);
    }

    #[Test]
    public function it_colours_the_title_of_an_invalid_field(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::password name="password" invalidated-title="true" />')
            ->assertSee('fc:group-[[invalid]]:text-danger', false);
    }

    #[Test]
    public function it_keeps_errors_while_typing_by_default(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::password name="password" />')
            ->assertDontSee('data-interactive', false);
    }

    #[Test]
    #[DataProvider('interactiveSwitches')]
    public function it_hides_errors_while_typing_when_interactive(string $attributes, bool $config): void
    {
        config(['form_components.interactive' => $config]);

        $this->withViewErrors([])
            ->blade('<x-form::password name="password" '.$attributes.' />')
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
    public function it_renders_an_image_icon(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::password name="password" title="Password" icon="/icons/password.png" />')
            ->assertSee('<img src="/icons/password.png" alt="">', false);
    }

    #[Test]
    public function it_inlines_an_svg_icon_file(): void
    {
        config(['form_components.image_formats' => ['.png']]);

        $this->withViewErrors([])
            ->blade('<x-form::password name="password" :icon="$icon" />', ['icon' => $this->iconFile()])
            ->assertSee($this->iconMarkup(), false);
    }

    #[Test]
    public function it_makes_room_for_the_visibility_toggle_and_icon(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::password name="password" />')
            ->assertSee('fc:pr-12');
    }

    #[Test]
    public function it_hides_the_hide_password_icon_until_toggled(): void
    {
        $view = (string) $this->withViewErrors([])->blade('<x-form::password name="password" />');

        $this->assertMatchesRegularExpression('/x-ref="hide"\s+hidden/', $view);
        $this->assertDoesNotMatchRegularExpression('/x-ref="show"\s+hidden/', $view);
    }

    #[Test]
    public function it_toggles_the_password_visibility(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::password name="password" />')
            ->assertSeeInOrder(['x-ref="input"', 'x-ref="show"', 'x-ref="hide"'], false);
    }

    #[Test]
    public function it_names_the_visibility_toggles(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::password name="password" />')
            ->assertSeeInOrder([
                'type="button"',
                'aria-label="Show password"',
                'aria-controls="password"',
                'x-ref="show"',
                'type="button"',
                'aria-label="Hide password"',
                'aria-controls="password"',
                'x-ref="hide"',
            ], false);
    }

    #[Test]
    public function it_leaves_out_the_visibility_toggle_when_disabled(): void
    {
        config(['form_components.password_visibility' => false]);

        $this->withViewErrors([])
            ->blade('<x-form::password name="password" />')
            ->assertDontSee('x-ref="show"', false)
            ->assertDontSee('x-ref="hide"', false);
    }

    #[Test]
    public function it_renders_image_visibility_icons(): void
    {
        config([
            'form_components.icons.password.show' => '/icons/show.png',
            'form_components.icons.password.hide' => '/icons/hide.png',
        ]);

        $this->withViewErrors([])
            ->blade('<x-form::password name="password" title="Password" />')
            ->assertSeeInOrder([
                '<img src="/icons/show.png" alt="">',
                '<img src="/icons/hide.png" alt="">',
            ], false);
    }

    #[Test]
    public function it_inlines_svg_visibility_icon_files(): void
    {
        config([
            'form_components.image_formats' => ['.png'],
            'form_components.icons.password.show' => $this->iconFile(),
            'form_components.icons.password.hide' => $this->iconFile(),
        ]);

        $this->withViewErrors([])
            ->blade('<x-form::password name="password" />')
            ->assertSeeInOrder([$this->iconMarkup(), $this->iconMarkup()], false);
    }
}
