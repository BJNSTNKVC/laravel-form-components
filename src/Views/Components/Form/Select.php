<?php

namespace Bjnstnkvc\FormComponents\Views\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Select extends Component
{
    /**
     * Select component name.
     */
    public $name;

    /**
     * Select component id.
     */
    public $id;

    /**
     * Select component title.
     */
    public $title;

    /**
     * Select component value.
     */
    public $values;

    /**
     * A base Model to iterate through.
     */
    public $model;

    /**
     * A Model key.
     */
    public $modelKey;

    /**
     * A Model value.
     */
    public $modelValue;

    /**
     * Select component placeholder.
     */
    public $placeholder;

    /**
     * Select component additional label classes.
     */
    public $label;

    /**
     * Select component label type.
     */
    public $labelType;

    /**
     * Select component Title invalidation state.
     */
    public $invalidatedTitle;

    /**
     * Select component default value.
     */
    public $default;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $id = null, $title = null, $values = null, $model = null, $modelKey = null, $modelValue = null, $placeholder = null, $label = null, $labelType = null, $invalidatedTitle = null, $default = null)
    {
        $this->name             = Str::slug($name, '_');
        $this->id               = $id ?: $this->name;
        $this->title            = $title ?: Str::title($name);
        $this->values           = $this->toArray($values) ?: [];
        $this->model            = $model;
        $this->modelKey         = $modelKey ?: 'id';
        $this->modelValue       = $modelValue;
        $this->placeholder      = $placeholder;
        $this->label            = $label;
        $this->labelType        = $labelType ?: config('form_components.label_type');
        $this->invalidatedTitle = filter_var($invalidatedTitle ?: config('form_components.invalidated_title'), FILTER_VALIDATE_BOOLEAN);
        $this->default          = $default;
    }

    /**
     * Convert String values to array.
     *
     * @param $values
     *
     * @return array
     */
    protected function toArray($values): array
    {
        // Initialize values array.
        $preparedValues = [];

        if (is_null($values)) {
            return [];
        }

        if (is_string($values)) {
            foreach (Str::of($values)->split('/(\s*,*\s*)*,+(\s*,*\s*)*/') as $value) {
                $exploded = Str::of($value)->explode(':');

                $preparedValues[$exploded->first()] = $exploded->last();
            }

            return $preparedValues;
        }

        return $values;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View
     */
    public function render()
    {
        return view('form-components::form.select');
    }
}
