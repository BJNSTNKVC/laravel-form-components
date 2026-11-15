<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Search\Tests\Feature;

use Bjnstnkvc\FormComponents\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class SearchTest extends TestCase
{
    #[Test]
    public function it_renders_a_search_input(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::search name="query" />')
            ->assertSee('type="search"', false)
            ->assertSee('name="query"', false);
    }

    #[Test]
    public function it_hides_the_browser_clear_button(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::search name="query" />')
            ->assertSee('fc:[&amp;::-webkit-search-cancel-button]:hidden', false);
    }

    #[Test]
    public function it_hides_the_placeholder_behind_a_floating_title(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::search name="query" title="Query" label-type="floating" />')
            ->assertSee('fc:focus:placeholder:text-transparent');
    }

    #[Test]
    public function it_renders_a_bottom_border_by_default(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::search name="query" title="Query" />')
            ->assertSee('fc:border-b-2')
            ->assertDontSee('fc:border-2');
    }

    #[Test]
    public function it_notches_the_border_for_a_floating_title(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::search name="query" title="Query" label-type="floating" border="full" />')
            ->assertSee('<fieldset', false)
            ->assertSee('aria-hidden="true"', false)
            ->assertSee('<legend><span class="fc:px-2">Query</span></legend>', false);
    }

    #[Test]
    #[DataProvider('unnotchedBorders')]
    public function it_does_not_notch_a_border_without_a_floating_title(string $attributes): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::search name="query" title="Query" '.$attributes.' />')
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
            ->blade('<x-form::search name="query" title="Query" border="full" border-radius="rounded" />')
            ->assertSee('fc:border-2')
            ->assertSee('fc:rounded-full')
            ->assertDontSee('fc:border-b-2');
    }

    #[Test]
    public function it_hides_the_clear_icon_until_typing(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::search name="query" />')
            ->assertSee('fc:*:[[class*=icon--clear]]:hidden', false);
    }

    #[Test]
    public function it_renders_an_image_icon(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::search name="query" title="Query" icon="/icons/query.png" />')
            ->assertSee('<img src="/icons/query.png" alt="Query icon">', false);
    }

    #[Test]
    public function it_inlines_an_svg_icon_file(): void
    {
        config(['form_components.image_formats' => ['.png']]);

        $this->withViewErrors([])
            ->blade('<x-form::search name="query" :icon="$icon" />', ['icon' => $this->iconFile()])
            ->assertSee($this->iconMarkup(), false);
    }

    #[Test]
    public function it_renders_an_image_clear_icon(): void
    {
        config(['form_components.icons.search.clear' => '/icons/clear.png']);

        $this->withViewErrors([])
            ->blade('<x-form::search name="query" title="Query" />')
            ->assertSee('<img src="/icons/clear.png" alt="Query icon" class="form-group__icon--clear">', false);
    }

    #[Test]
    public function it_inlines_an_svg_clear_icon_file(): void
    {
        config([
            'form_components.image_formats' => ['.png'],
            'form_components.icons.search.clear' => $this->iconFile(),
        ]);

        $this->withViewErrors([])
            ->blade('<x-form::search name="query" />')
            ->assertSee(str_replace('<svg ', '<svg class="form-group__icon--clear" ', $this->iconMarkup()), false);
    }
}
