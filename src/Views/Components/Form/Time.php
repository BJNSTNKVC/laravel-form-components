<?php

namespace Bjnstnkvc\FormComponents\Views\Components\Form;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Time extends Component
{
    /**
     * A Time component name.
     */
    public $name;

    /**
     * A Time component id.
     */
    public $id;

    /**
     * A Time component title.
     */
    public $title;

    /**
     * A Time component value.
     */
    public $value;

    /**
     * A Time component additional label classes.
     */
    public $label;

    /**
     * A Time component label type.
     */
    public $labelType;

    /**
     * Create a new component instance.
     *
     * @return void
     *
     * @throws Exception
     */
    public function __construct($name, $id = null, $title = null, $value = null, $label = null, $labelType = null)
    {
        $this->name      = Str::slug($name, '_');
        $this->id        = $id ?: $this->name;
        $this->title     = $title ?: Str::title($name);
        $this->value     = old($this->name) ?: ($value ? Carbon::parse($value)->toTimeString() : $value);
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
        return view('form-components::form.time');
    }
}
