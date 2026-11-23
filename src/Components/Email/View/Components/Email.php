<?php

namespace Bjnstnkvc\FormComponents\Components\Email\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Email extends Component
{
    /**
     * Email component name.
     */
    public $name;

    /**
     * Email component id.
     */
    public $id;

    /**
     * Email component title.
     */
    public $title;

    /**
     * Email component value.
     */
    public $value;

    /**
     * Email component additional label classes.
     */
    public $label;

    /**
     * Email component label type.
     */
    public $labelType;

    /**
     * Email component border style.
     */
    public $border;

    /**
     * Email component border radius.
     */
    public $borderRadius;

    /**
     * Email component Title invalidation state.
     */
    public $invalidatedTitle;

    /**
     * Whether Email component errors disappear on input.
     */
    public bool $interactive;

    /**
     * Email component icon visibility state.
     */
    public $showIcon;

    /**
     * Email component icon.
     */
    public $icon;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $id = null, $title = null, $value = null, $label = null, $labelType = null, $border = null, $borderRadius = null, $invalidatedTitle = null, bool|string|null $interactive = null, $showIcon = null, $icon = null)
    {
        $this->name = Str::slug($name, '_');
        $this->id = $id ?: $this->name;
        $this->title = $title ?: Str::title($name);
        $this->value = old($this->name) ?: $value;
        $this->label = $label;
        $this->labelType = $labelType ?: config('form_components.label_type');
        $this->border = $border ?: config('form_components.border');
        $this->borderRadius = $borderRadius ?: config('form_components.radius');
        $this->invalidatedTitle = filter_var($invalidatedTitle ?: config('form_components.invalidated_title'), FILTER_VALIDATE_BOOLEAN);
        $this->interactive = filter_var($interactive ?: config('form_components.interactive'), FILTER_VALIDATE_BOOLEAN);
        $this->showIcon = filter_var($showIcon ?: config('form_components.icon'), FILTER_VALIDATE_BOOLEAN);
        $this->icon = $this->renderIcon($icon ?: config('form_components.icons.email'));
    }

    /**
     * Render component icon depending on the type.
     *
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
            return '<img src="'.$icon.'" alt="">';
        }

        return $icon;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|\Closure|string
     */
    public function render()
    {
        return view('form-components::email');
    }
}
