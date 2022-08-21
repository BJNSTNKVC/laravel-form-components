<?php

namespace Bjnstnkvc\FormComponents\Views\Components\Form;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class Radio extends Component
{
    /**
     * Radio name.
     */
    public $name;

    /**
     * Radio title.
     */
    public $title;

    /**
     * Radio id.
     */
    public $id;

    /**
     * Radio value.
     */
    public $value;

    /**
     * Radio position.
     */
    public $position;

    /**
     * Radio component Title invalidation state.
     */
    public $invalidatedTitle;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $title = null, $id = null, $position = null, $invalidatedTitle = null)
    {
        $this->name             = Str::slug($name, '_');
        $this->title            = $title ?: Str::title($name);
        $this->id               = $id ?: $this->name;
        $this->value            = old($this->name) ? 'checked' : null;
        $this->position         = $position ?: config('form_components.position');
        $this->invalidatedTitle = filter_var($invalidatedTitle ?: config('form_components.invalidated_title'), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('laravel-form-components::form.radio');
    }
}
