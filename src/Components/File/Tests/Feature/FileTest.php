<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\File\Tests\Feature;

use Bjnstnkvc\FormComponents\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class FileTest extends TestCase
{
    #[Test]
    public function it_renders_a_file_input(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::file name="avatar" />')
            ->assertSee('type="file"', false)
            ->assertSee('name="avatar"', false);
    }

    #[Test]
    public function it_renders_a_bottom_border_by_default(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::file name="avatar" title="Avatar" />')
            ->assertSee('fc:border-b-2')
            ->assertDontSee('fc:border-2');
    }

    #[Test]
    public function it_renders_a_full_rounded_border(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::file name="avatar" title="Avatar" border="full" border-radius="rounded" />')
            ->assertSee('fc:border-2')
            ->assertSee('fc:rounded-full')
            ->assertDontSee('fc:border-b-2');
    }

    #[Test]
    public function it_notches_the_border_for_a_floating_title(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::file name="avatar" title="Avatar" label-type="floating" border="full" />')
            ->assertSee('<fieldset', false)
            ->assertSee('aria-hidden="true"', false)
            ->assertSee('<legend><span class="fc:px-2">Avatar</span></legend>', false);
    }

    #[Test]
    public function it_does_not_notch_a_border_without_a_floating_title(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::file name="avatar" title="Avatar" border="full" />')
            ->assertDontSee('<fieldset', false);

        $this->withViewErrors([])
            ->blade('<x-form::file name="avatar" title="Avatar" label-type="floating" />')
            ->assertDontSee('<fieldset', false);
    }

    #[Test]
    public function it_covers_the_field_with_the_file_input(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::file name="avatar" />')
            ->assertSee('fc:absolute fc:inset-0 fc:size-full fc:m-0 fc:p-0 fc:opacity-0');
    }

    #[Test]
    public function it_shows_the_title_as_the_placeholder(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::file name="avatar" title="Avatar" />')
            ->assertSee('placeholder="Avatar"', false)
            ->assertDontSee('placeholder=""', false);
    }

    #[Test]
    public function it_lets_the_placeholder_be_overridden(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::file name="avatar" title="Avatar" placeholder="Choose a picture" />')
            ->assertSee('placeholder="Choose a picture"', false)
            ->assertDontSee('placeholder="Avatar"', false);
    }

    #[Test]
    public function it_renders_an_image_icon(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::file name="avatar" title="Avatar" icon="/icons/avatar.png" />')
            ->assertSee('<img src="/icons/avatar.png" alt="Avatar icon">', false);
    }

    #[Test]
    public function it_inlines_an_svg_icon_file(): void
    {
        config(['form_components.image_formats' => ['.png']]);

        $this->withViewErrors([])
            ->blade('<x-form::file name="avatar" :icon="$icon" />', ['icon' => $this->iconFile()])
            ->assertSee($this->iconMarkup(), false);
    }
}
