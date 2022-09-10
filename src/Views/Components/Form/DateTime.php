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
     * DateTime component icon visibility state.
     */
    public $showIcon;

    /**
     * DateTime component icon.
     */
    public $icon;

    /**
     * Create a new component instance.
     *
     * @return void
     *
     * @throws Exception
     */
    public function __construct($name, $id = null, $title = null, $value = null, $label = null, $labelType = null, $border = null, $borderRadius = null, $invalidatedTitle = null, $showIcon = null, $icon = null)
    {
        $this->name             = Str::slug($name, '_');
        $this->id               = $id ?: $this->name;
        $this->title            = $title ?: Str::title($name);
        $this->value            = old($this->name) ?: ($value ? Carbon::parse($value)->toDateTimeString() : $value);
        $this->label            = $label;
        $this->labelType        = $labelType ?: config('form_components.label_type');
        $this->border           = $border ?: config('form_components.component_border');
        $this->borderRadius     = $borderRadius ?: config('form_components.component_radius');
        $this->invalidatedTitle = filter_var($invalidatedTitle ?: config('form_components.invalidated_title'), FILTER_VALIDATE_BOOLEAN);
        $this->showIcon         = filter_var($showIcon ?: config('form_components.component_icons'), FILTER_VALIDATE_BOOLEAN);
        $this->icon             = $this->renderIcon($icon ?: config('form_components.default_icons.date-time'));
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
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('form-components::form.date-time');
    }
}
