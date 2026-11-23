<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Textarea\Tests\Feature;

use Bjnstnkvc\FormComponents\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class TextareaTest extends TestCase
{
    #[Test]
    public function it_renders_a_textarea(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::textarea name="bio" />')
            ->assertSee('<textarea', false)
            ->assertSee('name="bio"', false);
    }

    #[Test]
    public function it_runs_on_alpine(): void
    {
        $this->withViewErrors(['bio' => 'The bio field is required.'])
            ->blade('<x-form::textarea name="bio" />')
            ->assertSee('x-data="form_textarea"', false)
            ->assertSee('x-ref="textarea"', false)
            ->assertSee('x-ref="error"', false);
    }

    #[Test]
    public function it_grows_with_its_content(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::textarea name="bio" />')
            ->assertSee('fc:h-auto fc:min-h-10 fc:py-2 fc:resize-none');
    }

    #[Test]
    public function it_hides_the_placeholder_behind_a_floating_title(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::textarea name="bio" title="Bio" label-type="floating" />')
            ->assertSee('fc:focus:placeholder:text-transparent');
    }

    #[Test]
    public function it_renders_a_bottom_border_by_default(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::textarea name="bio" title="Bio" />')
            ->assertSee('fc:border-b-2')
            ->assertDontSee('fc:border-2');
    }

    #[Test]
    public function it_notches_the_border_for_a_floating_title(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::textarea name="bio" title="Bio" label-type="floating" border="full" />')
            ->assertSee('<fieldset', false)
            ->assertSee('aria-hidden="true"', false)
            ->assertSee('<legend><span class="fc:px-2">Bio</span></legend>', false);
    }

    #[Test]
    #[DataProvider('unnotchedBorders')]
    public function it_does_not_notch_a_border_without_a_floating_title(string $attributes): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::textarea name="bio" title="Bio" '.$attributes.' />')
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
            ->blade('<x-form::textarea name="bio" title="Bio" border="full" border-radius="rounded" />')
            ->assertSee('fc:border-2')
            ->assertSee('fc:rounded-pill')
            ->assertDontSee('fc:border-b-2');
    }

    #[Test]
    public function it_leaves_the_title_colour_alone_by_default(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::textarea name="bio" />')
            ->assertDontSee('fc:group-[[invalid]]:text-danger', false);
    }

    #[Test]
    public function it_colours_the_title_of_an_invalid_field(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::textarea name="bio" invalidated-title="true" />')
            ->assertSee('fc:group-[[invalid]]:text-danger', false);
    }

    #[Test]
    public function it_keeps_errors_while_typing_by_default(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::textarea name="bio" />')
            ->assertDontSee('data-interactive', false);
    }

    #[Test]
    #[DataProvider('interactiveSwitches')]
    public function it_hides_errors_while_typing_when_interactive(string $attributes, bool $config): void
    {
        config(['form_components.interactive' => $config]);

        $this->withViewErrors([])
            ->blade('<x-form::textarea name="bio" '.$attributes.' />')
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
            ->blade('<x-form::textarea name="bio" title="Bio" icon="/icons/bio.png" />')
            ->assertSee('<img src="/icons/bio.png" alt="">', false);
    }

    #[Test]
    public function it_inlines_an_svg_icon_file(): void
    {
        config(['form_components.image_formats' => ['.png']]);

        $this->withViewErrors([])
            ->blade('<x-form::textarea name="bio" :icon="$icon" />', ['icon' => $this->iconFile()])
            ->assertSee($this->iconMarkup(), false);
    }

    #[Test]
    public function it_keeps_its_height_while_typing_by_default(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::textarea name="bio" />')
            ->assertDontSee('data-auto-expand', false);
    }

    #[Test]
    #[DataProvider('autoExpandSwitches')]
    public function it_grows_while_typing_when_auto_expanding(string $attributes, bool $config): void
    {
        config(['form_components.auto_expand' => $config]);

        $this->withViewErrors([])
            ->blade('<x-form::textarea name="bio" '.$attributes.' />')
            ->assertSee('data-auto-expand', false);
    }

    /**
     * Ways of making the textarea grow while typing.
     *
     * @return iterable<string, array{string, bool}>
     */
    public static function autoExpandSwitches(): iterable
    {
        yield 'attribute' => ['auto-expand="true"', false];
        yield 'config' => ['', true];
    }
}
