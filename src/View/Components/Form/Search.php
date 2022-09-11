<?php

namespace Bjnstnkvc\FormComponents\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Search extends Component
{
    /**
     * Search component name.
     */
    public $name;

    /**
     * Search component id.
     */
    public $id;

    /**
     * Search component title.
     */
    public $title;

    /**
     * Search component value.
     */
    public $value;

    /**
     * Search component additional label classes.
     */
    public $label;

    /**
     * Search component label type.
     */
    public $labelType;

    /**
     * Search component border style.
     */
    public $border;

    /**
     * Search component border radius.
     */
    public $borderRadius;

    /**
     * Search component Title invalidation state.
     */
    public $invalidatedTitle;

    /**
     * Search component icon visibility state.
     */
    public $showIcon;

    /**
     * @var bool Determine Search component clearing state.
     */
    public $searchClearing;

    /**
     * @var string Search component 'clear' icon.
     */
    public $searchClearIcon;

    /**
     * Search component icon.
     */
    public $icon;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $id = null, $title = null, $value = null, $label = null, $labelType = null, $border = null, $borderRadius = null, $invalidatedTitle = null, $showIcon = null, $searchClearing = null, $icon = null)
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
        $this->searchClearing  = filter_var($searchClearing ?: config('form_components.search_clearing'), FILTER_VALIDATE_BOOLEAN);
        $this->searchClearIcon = $this->renderSearchIcon(config('form_components.default_icons.search.clear'), 'clear');
        $this->icon            = $this->renderIcon($icon ?: config('form_components.default_icons.search.icon'));
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
    public function renderSearchIcon(string $icon, string $type)
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
        return view('form-components::form.search');
    }
}


