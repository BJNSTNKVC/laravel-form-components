<?php

namespace Bjnstnkvc\FormComponents\View\Components\Form;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class Base extends Component
{
    /**
     * Form Base component action.
     */
    public $action;

    /**
     * Form Base component ID.
     */
    public $id;

    /**
     * Form Base component method.
     */
    public $method;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($action, $id = null, $method = 'POST')
    {
        $this->action = $action;
        $this->id     = $id;
        $this->method = $method;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('form-components::form.base');
    }
}
