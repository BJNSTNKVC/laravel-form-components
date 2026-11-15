<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Select\Tests\Feature;

use Bjnstnkvc\FormComponents\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class SelectTest extends TestCase
{
    #[Test]
    public function it_renders_a_select(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::select name="country" values="Serbia, Spain" />')
            ->assertSee('<select', false)
            ->assertSee('name="country"', false)
            ->assertSee('Serbia', false)
            ->assertSee('Spain', false);
    }

    #[Test]
    public function it_renders_a_bottom_border_by_default(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::select name="country" title="Country" values="Serbia, Spain" />')
            ->assertSee('fc:border-b-2')
            ->assertDontSee('fc:border-2');
    }

    #[Test]
    public function it_renders_a_full_rounded_border(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::select name="country" title="Country" values="Serbia, Spain" border="full" border-radius="rounded" />')
            ->assertSee('fc:border-2')
            ->assertSee('fc:rounded-full')
            ->assertDontSee('fc:border-b-2');
    }

    #[Test]
    public function it_notches_the_border_for_a_floating_title(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::select name="country" title="Country" values="Serbia, Spain" label-type="floating" border="full" />')
            ->assertSee('<fieldset', false)
            ->assertSee('aria-hidden="true"', false)
            ->assertSee('<legend><span class="fc:px-2">Country</span></legend>', false);
    }

    #[Test]
    public function it_does_not_notch_a_border_without_a_floating_title(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::select name="country" title="Country" values="Serbia, Spain" border="full" />')
            ->assertDontSee('<fieldset', false);

        $this->withViewErrors([])
            ->blade('<x-form::select name="country" title="Country" values="Serbia, Spain" label-type="floating" />')
            ->assertDontSee('<fieldset', false);
    }

    #[Test]
    public function it_reveals_a_floating_title_once_a_value_is_set(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::select name="country" title="Country" values="Serbia, Spain" label-type="floating" />')
            ->assertSee("fc:peer-[[value='']]:opacity-0", false)
            ->assertSee('fc:-top-2');
    }

    #[Test]
    public function it_renders_a_taller_select_without_an_icon_when_multiple(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::select name="country" values="Serbia, Spain" multiple />')
            ->assertSee('fc:h-[88px]')
            ->assertDontSee('fc:*:grow');
    }

    #[Test]
    public function it_renders_an_image_icon(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::select name="country" title="Country" icon="/icons/country.png" />')
            ->assertSee('<img src="/icons/country.png" alt="Country icon">', false);
    }

    #[Test]
    public function it_inlines_an_svg_icon_file(): void
    {
        config(['form_components.image_formats' => ['.png']]);

        $this->withViewErrors([])
            ->blade('<x-form::select name="country" :icon="$icon" />', ['icon' => $this->iconFile()])
            ->assertSee($this->iconMarkup(), false);
    }

    #[Test]
    public function it_renders_no_options_without_values(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::select name="country" />')
            ->assertSee('<select', false)
            ->assertDontSee('<option', false);
    }

    #[Test]
    public function it_renders_options_from_an_array(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::select name="country" :values="$values" />', ['values' => ['rs' => 'Serbia', 'es' => 'Spain']])
            ->assertSee('value="rs"', false)
            ->assertSee('Serbia')
            ->assertSee('value="es"', false)
            ->assertSee('Spain');
    }
}
