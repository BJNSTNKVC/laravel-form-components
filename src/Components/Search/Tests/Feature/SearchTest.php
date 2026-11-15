<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\Search\Tests\Feature;

use Bjnstnkvc\FormComponents\Tests\TestCase;
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
    public function it_renders_a_bottom_border_by_default(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::search name="query" title="Query" />')
            ->assertSee('fc:border-b-2')
            ->assertDontSee('fc:border-2');
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
    public function it_notches_the_border_for_a_floating_title(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::search name="query" title="Query" label-type="floating" border="full" />')
            ->assertSee('<fieldset', false)
            ->assertSee('aria-hidden="true"', false)
            ->assertSee('<legend><span class="fc:px-2">Query</span></legend>', false);
    }

    #[Test]
    public function it_does_not_notch_a_border_without_a_floating_title(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::search name="query" title="Query" border="full" />')
            ->assertDontSee('<fieldset', false);

        $this->withViewErrors([])
            ->blade('<x-form::search name="query" title="Query" label-type="floating" />')
            ->assertDontSee('<fieldset', false);
    }

    #[Test]
    public function it_hides_the_placeholder_behind_a_floating_title(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::search name="query" title="Query" label-type="floating" />')
            ->assertSee('fc:focus:placeholder:text-transparent');
    }

    #[Test]
    public function it_hides_the_browser_clear_button(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::search name="query" />')
            ->assertSee('fc:[&amp;::-webkit-search-cancel-button]:hidden', false);
    }

    #[Test]
    public function it_hides_the_clear_icon_until_typing(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::search name="query" />')
            ->assertSee('fc:*:[[class*=icon--clear]]:hidden', false);
    }
}
