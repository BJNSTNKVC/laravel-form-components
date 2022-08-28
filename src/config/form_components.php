<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Form Components
    |--------------------------------------------------------------------------
    |
    | Here you may specify which components you would like to user as well
    | as their default behaviours.
    |
    */

    // List of all Components that are going to be loaded. Feel free to comment out the components you don't need.
    'components'        => [
        'button'    => Bjnstnkvc\FormComponents\Views\Components\Form\Button::class,
        'checkbox'  => Bjnstnkvc\FormComponents\Views\Components\Form\Checkbox::class,
        'date'      => Bjnstnkvc\FormComponents\Views\Components\Form\Date::class,
        'time'      => Bjnstnkvc\FormComponents\Views\Components\Form\Time::class,
        'date-time' => Bjnstnkvc\FormComponents\Views\Components\Form\DateTime::class,
        'error'     => Bjnstnkvc\FormComponents\Views\Components\Form\Error::class,
        'input'     => Bjnstnkvc\FormComponents\Views\Components\Form\Input::class,
        'password'  => Bjnstnkvc\FormComponents\Views\Components\Form\Password::class,
        'radio'     => Bjnstnkvc\FormComponents\Views\Components\Form\Radio::class,
        'select'    => Bjnstnkvc\FormComponents\Views\Components\Form\Select::class,
        'textarea'  => Bjnstnkvc\FormComponents\Views\Components\Form\Textarea::class,
    ],

    // Path used to publish component config files.
    'publish_path'      => public_path('vendor/form-components'),

    // Form Components prefix (E.g. <x-form-button>).
    'prefix'            => env('COMPONENT_PREFIX', 'form'),

    // Form Components separator (E.g. <x-form::button>).
    'separator'         => env('COMPONENT_SEPARATOR', '::'),

    // Form Components default label type (options: column, row, floating).
    'label_type'        => env('COMPONENT_LABEL', 'column'),

    // Form Components default border style (options: bottom, full).
    'component_border'  => env('COMPONENT_BORDER', 'full'),

    // Form Components default border radius (options: squared, rounded-s, rounded).
    'component_radius'  => env('COMPONENT_RADIUS', 'rounded-s'),

    // Form Components default checkbox/radio position (options: left, center).
    'position'          => env('COMPONENT_POSITION', 'center'),

    // Form Components default button radius (options: squared, rounded-s, rounded-m, rounded).
    'button_radius'     => env('BUTTON_RADIUS', 'rounded'),

    // Determine whether the Component title would change on invalid input.
    'invalidated_title' => env('INVALIDATED_TITLE', false),

    // Determine whether the validation errors should disappear on input.
    'interactive'       => env('INTERACTIVE_COMPONENT', false),

    // Determine whether the Text Area height should expand with input.
    'auto_expand'       => env('AUTOEXPAND_TEXTAREA', false),
];
