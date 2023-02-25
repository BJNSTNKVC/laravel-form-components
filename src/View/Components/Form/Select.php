<?php

namespace Bjnstnkvc\FormComponents\View\Components\Form;

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
     * A Model JavaScript data attribute.
     */
    public $jsDataKey;

    /**
     * A Model JavaScript key.
     */
    public $jsDataValue;

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
     * Select component border style.
     */
    public $border;

    /**
     * Select component border radius.
     */
    public $borderRadius;

    /**
     * Select component Title invalidation state.
     */
    public $invalidatedTitle;

    /**
     * Select component icon visibility state.
     */
    public $showIcon;

    /**
     * Select component icon.
     */
    public $icon;

    /**
     * Select component default value.
     */
    public $default;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $id = null, $title = null, $values = null, $model = null, $modelKey = null, $modelValue = null, $jsDataKey = null, $jsDataValue = null, $placeholder = null, $label = null, $labelType = null, $border = null, $borderRadius = null, $invalidatedTitle = null, $showIcon = null, $icon = null, $default = null)
    {
        $this->name             = Str::slug($name, '_');
        $this->id               = $id ?: $this->name;
        $this->title            = $title ?: Str::title($name);
        $this->values           = $this->toArray($values) ?: [];
        $this->model            = $model;
        $this->modelKey         = $modelKey ?: 'id';
        $this->modelValue       = $modelValue;
        $this->jsDataKey        = 'data-' . $jsDataKey . '=';
        $this->jsDataValue      = $jsDataValue;
        $this->placeholder      = $placeholder;
        $this->label            = $label;
        $this->labelType        = $labelType ?: config('form_components.label_type');
        $this->border           = $border ?: config('form_components.border');
        $this->borderRadius     = $borderRadius ?: config('form_components.radius');
        $this->invalidatedTitle = filter_var($invalidatedTitle ?: config('form_components.invalidated_title'), FILTER_VALIDATE_BOOLEAN);
        $this->showIcon         = filter_var($showIcon ?: config('form_components.icon'), FILTER_VALIDATE_BOOLEAN);
        $this->icon             = $this->renderIcon($icon ?: config('form_components.icons.select'));
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
     * Render component icon depending on the type.
     *
     * @param null|string $icon
     *
     * @return false|string
     */
    public function renderIcon(?string $icon)
    {
        // If an icon is a 'svg' file, and it's not part of 'image_formats' array, render an icon as '<svg>'.
        if (Str::contains($icon, '.svg') && ! in_array('.svg', config('form_components.image_formats'))) {
            return file_get_contents($icon);
        }

        // If an icon is part of 'image_formats' array, render an icon as '<img>'
        if (Str::contains($icon, config('form_components.image_formats'))) {
            return '<img src="' . $icon . '" alt="' . $this->title . ' icon">';
        }

        return $icon;
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
