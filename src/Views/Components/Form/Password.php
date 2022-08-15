<?php

namespace Bjnstnkvc\FormComponents\Views\Components\Form;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class Password extends Component
{
    /**
     * A Password component name.
     */
    public $name;

    /**
     * A Password component id.
     */
    public $id;

    /**
     * A Password component title.
     */
    public $title;

    /**
     * A Password component value.
     */
    public $value;

    /**
     * A Password component additional label classes.
     */
    public $label;

    /**
     * A Password component label type.
     */
    public $labelType;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $id = null, $title = null, $value = null, $label = null, $labelType = null)
    {
        $this->name      = Str::slug($name, '_');
        $this->id        = $id ?: $this->name;
        $this->title     = $title ?: Str::title($name);
        $this->value     = old($this->name) ?: $value;
        $this->label     = $label;
        $this->labelType = $labelType ?: config('form_components.label_type');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('form-components::form.password');
    }
}
