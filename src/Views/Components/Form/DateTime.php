<?php

namespace Bjnstnkvc\FormComponents\Views\Components\Form;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class DateTime extends Component
{
    /**
     * DateTime component name.
     */
    public $name;

    /**
     * DateTime component id.
     */
    public $id;

    /**
     * DateTime component title.
     */
    public $title;

    /**
     * DateTime component value.
     */
    public $value;

    /**
     * DateTime component additional label classes.
     */
    public $label;

    /**
     * DateTime component label type.
     */
    public $labelType;

    /**
     * DateTime component border style.
     */
    public $border;

    /**
     * DateTime component border radius.
     */
    public $borderRadius;

    /**
     * DateTime component Title invalidation state.
     */
    public $invalidatedTitle;

    /**
     * Create a new component instance.
     *
     * @return void
     *
     * @throws Exception
     */
    public function __construct($name, $id = null, $title = null, $value = null, $label = null, $labelType = null, $border = null, $borderRadius = null, $invalidatedTitle = null)
    {
        $this->name             = Str::slug($name, '_');
        $this->id               = $id ?: $this->name;
        $this->title            = $title ?: Str::title($name);
        $this->value            = old($this->name) ?: ($value ? Carbon::parse($value)->toDateTimeLocalString() : $value);
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
        return view('form-components::form.date-time');
    }
}
