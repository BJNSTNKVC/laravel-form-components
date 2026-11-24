<?php

namespace Bjnstnkvc\FormComponents\Components\Date\View\Components;

use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\View;
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
     * Date component border style.
     */
    public $border;

    /**
     * Date component border radius.
     */
    public $borderRadius;

    /**
     * Date component Title invalidation state.
     */
    public $invalidatedTitle;

    /**
     * Whether Date component errors disappear on input.
     */
    public bool $interactive;

    /**
     * Date component icon visibility state.
     */
    public $showIcon;

    /**
     * Date component icon.
     */
    public $icon;

    /**
     * Create a new component instance.
     *
     * @return void
     *
     * @throws Exception
     */
    public function __construct($name, $id = null, $title = null, $value = null, $label = null, $labelType = null, $border = null, $borderRadius = null, $invalidatedTitle = null, bool|string|null $interactive = null, $showIcon = null, $icon = null)
    {
        $this->name = Str::slug($name, '_');
        $this->id = $id ?: $this->name;
        $this->title = $title ?: Str::title($name);
        $this->value = old($this->name) ?: ($value ? Carbon::parse($value)->toDateString() : $value);
        $this->label = $label;
        $this->labelType = $labelType ?: config('form_components.label_type');
        $this->border = $border ?: config('form_components.border');
        $this->borderRadius = $borderRadius ?: config('form_components.radius');
        $this->invalidatedTitle = filter_var($invalidatedTitle ?: config('form_components.invalidated_title'), FILTER_VALIDATE_BOOLEAN);
        $this->interactive = filter_var($interactive ?: config('form_components.interactive'), FILTER_VALIDATE_BOOLEAN);
        $this->showIcon = filter_var($showIcon ?: config('form_components.icon'), FILTER_VALIDATE_BOOLEAN);
        $this->icon = $this->renderIcon($icon ?: config('form_components.icons.date'));
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
        return view('form-components::date');
    }
}
