<?php

namespace Bjnstnkvc\FormComponents\Views\Components\Form;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class Button extends Component
{
    /**
     * A Button name.
     */
    public $name;

    /**
     * A Button id.
     */
    public $id;

    /**
     * A Button title.
     */
    public $title;

    /**
     * A Button link.
     */
    public $link;

    /**
     * A Button border radius.
     */
    public $borderRadius;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $id = null, $title = null, $link = null, $borderRadius = null)
    {
        $this->name         = $name;
        $this->id           = $id ?: Str::camel($name);
        $this->title        = $title ?: Str::title($name);
        $this->link         = $link;
        $this->borderRadius = $borderRadius ?: config('form_components.button_radius');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('form-components::form.button');
    }
}