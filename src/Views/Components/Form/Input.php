<?php

namespace Bjnstnkvc\FormComponents\Views\Components\Form;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class Input extends Component
{
    /**
     * An Input component name.
     */
    public $name;

    /**
     * An Input component id.
     */
    public $id;

    /**
     * An Input component title.
     */
    public $title;

    /**
     * An Input component value.
     */
    public $value;

    /**
     * An Input component additional label classes.
     */
    public $label;

    /**
     * An Input component label type.
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
        return view('form-components::form.input');
    }
}
