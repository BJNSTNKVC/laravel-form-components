<?php

namespace Bjnstnkvc\FormComponents\Views\Components\Form;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class File extends Component
{
    /**
     * Input component name.
     */
    public $name;

    /**
     * Input component id.
     */
    public $id;

    /**
     * Input component title.
     */
    public $title;

    /**
     * Input component value.
     */
    public $value;

    /**
     * Input component additional label classes.
     */
    public $label;

    /**
     * Input component label type.
     */
    public $labelType;

    /**
     * Input component border style.
     */
    public $border;

    /**
     * Input component border radius.
     */
    public $borderRadius;

    /**
     * Input component Title invalidation state.
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
        return view('form-components::form.file');
    }
}