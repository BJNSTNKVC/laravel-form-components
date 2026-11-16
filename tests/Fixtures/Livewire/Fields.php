<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Tests\Fixtures\Livewire;

use Livewire\Component;

class Fields extends Component
{
    /**
     * Whether the field added on request is shown.
     */
    public bool $added = false;

    /**
     * Add a field the first render left out.
     */
    public function add(): void
    {
        $this->added = true;
    }

    /**
     * Render a password, and a search once added.
     */
    public function render(): string
    {
        return <<<'BLADE'
            <div>
                <x-form::password
                    name="password"
                    title="Password"
                />

                @if($added)
                    <x-form::search
                        name="search"
                        title="Search"
                    />
                @endif

                <button
                    id="add"
                    type="button"
                    wire:click="add"
                >
                    Add a Field
                </button>
            </div>
            BLADE;
    }
}
