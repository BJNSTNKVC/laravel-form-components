<?php

namespace Bjnstnkvc\FormComponents\Components\Error\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Error extends Component
{
    /**
     * Error component name.
     */
    public $name;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|\Closure|string
     */
    public function render()
    {
        return view('form-components::error');
    }
}
