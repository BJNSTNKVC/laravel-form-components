<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Time\Tests\Feature;

use Bjnstnkvc\FormComponents\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class TimeTest extends TestCase
{
    #[Test]
    public function it_renders_a_time_input(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::time name="alarm" />')
            ->assertSee('type="time"', false)
            ->assertSee('name="alarm"', false);
    }

    #[Test]
    public function it_hides_the_native_picker_indicator(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::time name="alarm" />')
            ->assertSee('fc:[&amp;::-webkit-calendar-picker-indicator]:opacity-0', false)
            ->assertSee('fc:[&amp;::-webkit-inner-spin-button]:opacity-0', false);
    }

    #[Test]
    public function it_reveals_a_floating_title_once_a_value_is_set(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::time name="alarm" title="Alarm" label-type="floating" />')
            ->assertSee("fc:peer-[[value='']]:opacity-0", false)
            ->assertSee('fc:-top-2');
    }

    #[Test]
    public function it_renders_a_bottom_border_by_default(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::time name="alarm" title="Alarm" />')
            ->assertSee('fc:border-b-2')
            ->assertDontSee('fc:border-2');
    }

    #[Test]
    public function it_notches_the_border_for_a_floating_title(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::time name="alarm" title="Alarm" label-type="floating" border="full" />')
            ->assertSee('<fieldset', false)
            ->assertSee('aria-hidden="true"', false)
            ->assertSee('<legend><span class="fc:px-2">Alarm</span></legend>', false);
    }

    #[Test]
    #[DataProvider('unnotchedBorders')]
    public function it_does_not_notch_a_border_without_a_floating_title(string $attributes): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::time name="alarm" title="Alarm" '.$attributes.' />')
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
            ->blade('<x-form::time name="alarm" title="Alarm" border="full" border-radius="rounded" />')
            ->assertSee('fc:border-2')
            ->assertSee('fc:rounded-pill')
            ->assertDontSee('fc:border-b-2');
    }

    #[Test]
    public function it_renders_an_image_icon(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::time name="alarm" title="Alarm" icon="/icons/alarm.png" />')
            ->assertSee('<img src="/icons/alarm.png" alt="Alarm icon">', false);
    }

    #[Test]
    public function it_inlines_an_svg_icon_file(): void
    {
        config(['form_components.image_formats' => ['.png']]);

        $this->withViewErrors([])
            ->blade('<x-form::time name="alarm" :icon="$icon" />', ['icon' => $this->iconFile()])
            ->assertSee($this->iconMarkup(), false);
    }
}
