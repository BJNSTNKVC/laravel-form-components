<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\DateTime\Tests\Feature;

use Bjnstnkvc\FormComponents\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class DateTimeTest extends TestCase
{
    #[Test]
    public function it_renders_a_date_time_input(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::date-time name="starts_at" />')
            ->assertSee('type="datetime-local"', false)
            ->assertSee('name="starts_at"', false);
    }

    #[Test]
    public function it_hides_the_native_picker_indicator(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::date-time name="starts_at" />')
            ->assertSee('fc:[&amp;::-webkit-calendar-picker-indicator]:opacity-0', false)
            ->assertSee('fc:[&amp;::-webkit-inner-spin-button]:opacity-0', false);
    }

    #[Test]
    public function it_reveals_a_floating_title_once_a_value_is_set(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::date-time name="starts_at" title="Starts At" label-type="floating" />')
            ->assertSee("fc:peer-[[value='']]:opacity-0", false)
            ->assertSee('fc:-top-2');
    }

    #[Test]
    public function it_renders_a_bottom_border_by_default(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::date-time name="starts_at" title="Starts At" />')
            ->assertSee('fc:border-b-2')
            ->assertDontSee('fc:border-2');
    }

    #[Test]
    public function it_notches_the_border_for_a_floating_title(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::date-time name="starts_at" title="Starts At" label-type="floating" border="full" />')
            ->assertSee('<fieldset', false)
            ->assertSee('aria-hidden="true"', false)
            ->assertSee('<legend><span class="fc:px-2">Starts At</span></legend>', false);
    }

    #[Test]
    #[DataProvider('unnotchedBorders')]
    public function it_does_not_notch_a_border_without_a_floating_title(string $attributes): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::date-time name="starts_at" title="Starts At" '.$attributes.' />')
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
            ->blade('<x-form::date-time name="starts_at" title="Starts At" border="full" border-radius="rounded" />')
            ->assertSee('fc:border-2')
            ->assertSee('fc:rounded-pill')
            ->assertDontSee('fc:border-b-2');
    }

    #[Test]
    public function it_renders_an_image_icon(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::date-time name="starts_at" title="Starts At" icon="/icons/starts_at.png" />')
            ->assertSee('<img src="/icons/starts_at.png" alt="Starts At icon">', false);
    }

    #[Test]
    public function it_inlines_an_svg_icon_file(): void
    {
        config(['form_components.image_formats' => ['.png']]);

        $this->withViewErrors([])
            ->blade('<x-form::date-time name="starts_at" :icon="$icon" />', ['icon' => $this->iconFile()])
            ->assertSee($this->iconMarkup(), false);
    }
}
