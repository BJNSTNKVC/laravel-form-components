<?php

namespace Bjnstnkvc\FormComponents\Views\Components\Form;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Date extends Component
{
    /**
     * Date component name.
     */
    public $name;

    /**
     * Date component id.
     */
    public $id;

    /**
     * Date component title.
     */
    public $title;

    /**
     * Date component value.
     */
    public $value;

    /**
     * Date component additional abel classes.
     */
    public $label;

    /**
     * Date component label type.
     */
    public $labelType;

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
    public function __construct($name, $id = null, $title = null, $value = null, $label = null, $labelType = null, $invalidatedTitle = null)
    {
        $this->name             = Str::slug($name, '_');
        $this->id               = $id ?: $this->name;
        $this->title            = $title ?: Str::title($name);
        $this->value            = old($this->name) ?: ($value ? Carbon::parse($value)->toDateString() : $value);
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
        return view('form-components::form.date');
    }
}
