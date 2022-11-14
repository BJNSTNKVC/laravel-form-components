<?php

namespace Bjnstnkvc\FormComponents\View\Components\Form;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class Checkbox extends Component
{
    /**
     * Checkbox component name.
     */
    public $name;

    /**
     * Checkbox component title.
     */
    public $title;

    /**
     * Checkbox component id.
     */
    public $id;

    /**
     * Checkbox component value.
     */
    public $value;

    /**
     * Checkbox component position.
     */
    public $position;

    /**
     * Checkbox component additional abel classes.
     */
    public $label;

    /**
     * Checkbox component Title invalidation state.
     */
    public $invalidatedTitle;

    /**
     * Checkbox component style.
     */
    public $switch;

    /**
     * Checkbox component checked status.
     */
    public $checked;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $title = null, $id = null, $position = null, $label = null, $invalidatedTitle = null, $switch = null, $checked = null)
    {
        $this->name             = Str::slug($name, '_');
        $this->title            = $title ?: Str::title($name);
        $this->id               = $id ?: $this->name;
        $this->value            = old($this->name) ? 'checked' : null;
        $this->position         = $position ?: config('form_components.position');
        $this->label            = $label;
        $this->invalidatedTitle = filter_var($invalidatedTitle ?: config('form_components.invalidated_title'), FILTER_VALIDATE_BOOLEAN);
        $this->switch           = filter_var($switch  ?: config('form_components.switch'), FILTER_VALIDATE_BOOLEAN);
        $this->checked          = filter_var($checked, FILTER_VALIDATE_BOOLEAN);
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
