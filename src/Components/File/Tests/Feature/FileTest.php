<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\File\Tests\Feature;

use Bjnstnkvc\FormComponents\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
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
    public function it_runs_on_alpine(): void
    {
        $this->withViewErrors(['avatar' => 'The avatar field is required.'])
            ->blade('<x-form::file name="avatar" />')
            ->assertSee('x-data="form_file"', false)
            ->assertSee('x-ref="input"', false)
            ->assertSee('x-ref="error"', false);
    }

    #[Test]
    public function it_shows_the_chosen_files_in_the_field(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::file name="avatar" />')
            ->assertSeeInOrder(['x-ref="input"', 'x-ref="holder"'], false);
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
    public function it_renders_a_bottom_border_by_default(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::file name="avatar" title="Avatar" />')
            ->assertSee('fc:border-b-2')
            ->assertDontSee('fc:border-2');
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
    #[DataProvider('unnotchedBorders')]
    public function it_does_not_notch_a_border_without_a_floating_title(string $attributes): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::file name="avatar" title="Avatar" '.$attributes.' />')
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
            ->blade('<x-form::file name="avatar" title="Avatar" border="full" border-radius="rounded" />')
            ->assertSee('fc:border-2')
            ->assertSee('fc:rounded-pill')
            ->assertDontSee('fc:border-b-2');
    }

    #[Test]
    public function it_leaves_the_title_colour_alone_by_default(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::file name="avatar" />')
            ->assertDontSee('fc:group-[[invalid]]:text-danger', false);
    }

    #[Test]
    public function it_colours_the_title_of_an_invalid_field(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::file name="avatar" invalidated-title="true" />')
            ->assertSee('fc:group-[[invalid]]:text-danger', false);
    }

    #[Test]
    public function it_keeps_errors_while_typing_by_default(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::file name="avatar" />')
            ->assertDontSee('data-interactive', false);
    }

    #[Test]
    #[DataProvider('interactiveSwitches')]
    public function it_hides_errors_while_typing_when_interactive(string $attributes, bool $config): void
    {
        config(['form_components.interactive' => $config]);

        $this->withViewErrors([])
            ->blade('<x-form::file name="avatar" '.$attributes.' />')
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
