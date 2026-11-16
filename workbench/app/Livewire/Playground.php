<?php

declare(strict_types=1);

namespace Workbench\App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Playground extends Component
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
     * Render the component.
     */
    public function render(): View
    {
        return view('livewire.playground');
    }
}
