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
    public function it_renders_an_image_icon(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::password name="password" title="Password" icon="/icons/password.png" />')
            ->assertSee('<img src="/icons/password.png" alt="Password icon">', false);
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
        $this->withViewErrors([])
            ->blade('<x-form::password name="password" />')
            ->assertSee('fc:*:[[class*=icon--hide]]:hidden', false);
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
            ->assertSee('<img src="/icons/show.png" alt="Password icon" class="form-group__icon--show">', false)
            ->assertSee('<img src="/icons/hide.png" alt="Password icon" class="form-group__icon--hide">', false);
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
            ->assertSee(str_replace('<svg ', '<svg class="form-group__icon--show" ', $this->iconMarkup()), false)
            ->assertSee(str_replace('<svg ', '<svg class="form-group__icon--hide" ', $this->iconMarkup()), false);
    }
}
