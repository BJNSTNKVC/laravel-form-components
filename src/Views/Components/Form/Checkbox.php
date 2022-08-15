<?php

namespace Bjnstnkvc\FormComponents\Views\Components\Form;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class Checkbox extends Component
{
    /**
     * A Checkbox name.
     */
    public $name;

    /**
     * A Checkbox title.
     */
    public $title;

    /**
     * A Checkbox id.
     */
    public $id;

    /**
     * A Checkbox value.
     */
    public $value;

    /**
     * A Checkbox position.
     */
    public $position;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $title = null, $id = null, $position = null)
    {
        $this->name     = Str::slug($name, '_');
        $this->title    = $title ?: Str::title($name);
        $this->id       = $id ?: $this->name;
        $this->value    = old($this->name) ? 'checked' : null;
        $this->position = $position ?: config('form_components.position');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('form-components::form.checkbox');
    }
}
