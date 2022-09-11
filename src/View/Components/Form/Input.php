<?php

namespace Bjnstnkvc\FormComponents\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Input extends Component
{
    /**
     * Input component name.
     */
    public $name;

    /**
     * Input component id.
     */
    public $id;

    /**
     * Input component title.
     */
    public $title;

    /**
     * Input component value.
     */
    public $value;

    /**
     * Input component additional label classes.
     */
    public $label;

    /**
     * Input component label type.
     */
    public $labelType;

    /**
     * Input component border style.
     */
    public $border;

    /**
     * Input component border radius.
     */
    public $borderRadius;

    /**
     * Input component Title invalidation state.
     */
    public $invalidatedTitle;

    /**
     * Input component icon visibility state.
     */
    public $showIcon;

    /**
     * Input component icon.
     */
    public $icon;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $id = null, $title = null, $value = null, $label = null, $labelType = null, $border = null, $borderRadius = null, $invalidatedTitle = null, $showIcon = null, $icon = null)
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
        $this->showIcon         = filter_var($showIcon ?: config('form_components.component_icons'), FILTER_VALIDATE_BOOLEAN);
        $this->icon             = $this->renderIcon($icon ?: config('form_components.default_icons.input'));
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
     * @return View|Closure|string
     */
    public function render()
    {
        return view('form-components::form.input');
    }
}
