<?php

namespace Bjnstnkvc\FormComponents\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Password extends Component
{
    /**
     * Password component name.
     */
    public $name;

    /**
     * Password component id.
     */
    public $id;

    /**
     * Password component title.
     */
    public $title;

    /**
     * Password component value.
     */
    public $value;

    /**
     * Password component additional label classes.
     */
    public $label;

    /**
     * Password component label type.
     */
    public $labelType;

    /**
     * Password component border style.
     */
    public $border;

    /**
     * Password component border radius.
     */
    public $borderRadius;

    /**
     * Password component title invalidation state.
     */
    public $invalidatedTitle;

    /**
     * Password component icon visibility state.
     */
    public $showIcon;

    /**
     * @var bool Determine Password component visibility state.
     */
    public $passwordVisibility;

    /**
     * @var string Password component 'show' icon.
     */
    public $showPasswordIcon;

    /**
     * @var string Password component 'hide' icon.
     */
    public $hidePasswordIcon;

    /**
     * Password component icon.
     */
    public $icon;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $id = null, $title = null, $value = null, $label = null, $labelType = null, $border = null, $borderRadius = null, $invalidatedTitle = null, $showIcon = null, $icon = null, $passwordVisibility = null)
    {
        $this->name               = Str::slug($name, '_');
        $this->id                 = $id ?: $this->name;
        $this->title              = $title ?: Str::title($name);
        $this->value              = old($this->name) ?: $value;
        $this->label              = $label;
        $this->labelType          = $labelType ?: config('form_components.label_type');
        $this->border             = $border ?: config('form_components.border');
        $this->borderRadius       = $borderRadius ?: config('form_components.radius');
        $this->invalidatedTitle   = filter_var($invalidatedTitle ?: config('form_components.invalidated_title'), FILTER_VALIDATE_BOOLEAN);
        $this->showIcon           = filter_var($showIcon ?: config('form_components.icon'), FILTER_VALIDATE_BOOLEAN);
        $this->passwordVisibility = filter_var($passwordVisibility ?: config('form_components.password_visibility'), FILTER_VALIDATE_BOOLEAN);
        $this->showPasswordIcon   = $this->renderVisibilityIcon(config('form_components.icons.password.show'), 'show');
        $this->hidePasswordIcon   = $this->renderVisibilityIcon(config('form_components.icons.password.hide'), 'hide');
        $this->icon               = $this->renderIcon($icon ?: config('form_components.icons.password.icon'));
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
     * Render component password visibility icons depending on the type.
     *
     * @param string $icon
     * @param string $type
     *
     * @return false|string
     */
    public function renderVisibilityIcon(string $icon, string $type)
    {
        // If an icon is a 'svg' file, and it's not part of 'image_formats' array, render an icon as '<svg>'.
        if (Str::contains($icon, '.svg') && ! in_array('.svg', config('form_components.image_formats'))) {
            return file_get_contents(Str::replaceFirst('<svg ', '<svg class="form-group__icon--' . $type . '" ', $icon));
        }

        // If an icon is part of 'image_formats' array, render an icon as '<img>'
        if (Str::contains($icon, config('form_components.image_formats'))) {
            return '<img src="' . $icon . '" alt="' . $this->title . ' icon" class="form-group__icon--' . $type . '">';
        }

        return Str::replaceFirst('<svg ', '<svg class="form-group__icon--' . $type . '" ', $icon);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render()
    {
        return view('form-components::form.password');
    }
}

