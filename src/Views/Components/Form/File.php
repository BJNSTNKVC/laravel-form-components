<?php

namespace Bjnstnkvc\FormComponents\Views\Components\Form;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class File extends Component
{
    /**
     * File component name.
     */
    public $name;

    /**
     * File component id.
     */
    public $id;

    /**
     * File component title.
     */
    public $title;

    /**
     * File component value.
     */
    public $value;

    /**
     * File component additional label classes.
     */
    public $label;

    /**
     * File component label type.
     */
    public $labelType;

    /**
     * File component border style.
     */
    public $border;

    /**
     * File component border radius.
     */
    public $borderRadius;

    /**
     * File component Title invalidation state.
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
