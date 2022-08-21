<?php

namespace Bjnstnkvc\FormComponents\Views\Components\Form;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class Textarea extends Component
{
    /**
     * Textarea component name.
     */
    public $name;

    /**
     * Textarea component id.
     */
    public $id;

    /**
     * Textarea component title.
     */
    public $title;

    /**
     * Textarea component value.
     */
    public $value;

    /**
     * Textarea component additional label classes.
     */
    public $label;

    /**
     * Textarea component label type.
     */
    public $labelType;

    /**
     * Textarea component Title invalidation state.
     */
    public $invalidatedTitle;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $id = null, $title = null, $value = null, $label = null, $labelType = null, $invalidatedTitle = null)
    {
        $this->name             = Str::slug($name, '_');
        $this->id               = $id ?: $this->name;
        $this->title            = $title ?: Str::title($name);
        $this->value            = old($this->name) ?: $value;
        $this->label            = $label;
        $this->labelType        = $labelType ?: config('form_components.label_type');
        $this->invalidatedTitle = filter_var($invalidatedTitle ?: config('form_components.invalidated_title'), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('laravel-form-components::form.textarea');
    }
}
