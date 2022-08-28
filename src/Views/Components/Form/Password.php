<?php

namespace Bjnstnkvc\FormComponents\Views\Components\Form;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class Password extends Component
{
    /**
     * Password component name.
     */
    public $name;

    /**
     * Password component id.
     */
    public $id;

    /**
     * Password component title.
     */
    public $title;

    /**
     * Password component value.
     */
    public $value;

    /**
     * Password component additional label classes.
     */
    public $label;

    /**
     * Password component label type.
     */
    public $labelType;

    /**
     * Password component border style.
     */
    public $border;

    /**
     * Password component border radius.
     */
    public $borderRadius;

    /**
     * Password component Title invalidation state.
     */
    public $invalidatedTitle;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $id = null, $title = null, $value = null, $label = null, $labelType = null, $border = null, $borderRadius = null, $invalidatedTitle = null)
    {
        $this->name             = Str::slug($name, '_');
        $this->id               = $id ?: $this->name;
        $this->title            = $title ?: Str::title($name);
        $this->value            = old($this->name) ?: $value;
        $this->label            = $label;
        $this->labelType        = $labelType ?: config('form_components.label_type');
        $this->border           = $border ?: config('form_components.component_border');
        $this->borderRadius     = $borderRadius ?: config('form_components.component_radius');
        $this->invalidatedTitle = filter_var($invalidatedTitle ?: config('form_components.invalidated_title'), FILTER_VALIDATE_BOOLEAN);
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
