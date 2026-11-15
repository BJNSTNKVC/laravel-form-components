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
}
