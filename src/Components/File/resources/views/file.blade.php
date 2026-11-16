@php($bottom = $border === 'bottom')
@php($notched = $labelType === 'floating' && ! $bottom)
@php($hasIcon = $showIcon && $icon)

<label
    @class([
        'fc-form-group fc:group fc:relative fc:grid fc:auto-rows-max fc:text-left fc:text-sm fc:leading-4 fc:[.fc-form-group+&]:mt-4 fc:font-base fc:dark:scheme-dark',
        "fc:grid-cols-1 fc:[grid-template-areas:'title'_'input'_'error']" => $labelType === 'column',
        "fc:grid-cols-[min-content_1fr] fc:[grid-template-areas:'title_input'_'._error']" => $labelType === 'row',
        "fc:grid-cols-1 fc:[grid-template-areas:'input'_'error']" => $labelType === 'floating',
        $label,
    ])
    @error($name) invalid @enderror
    for="{{ $name }}"
>
    <input
        {{ $attributes->class('fc:absolute fc:inset-0 fc:size-full fc:m-0 fc:p-0 fc:opacity-0 fc:cursor-pointer fc:file:hidden') }}
        type="file"
        id="{{ $id }}"
        name="{{ $name }}"
        value="{{ $value }}"
        invalidated-title="{{ $invalidatedTitle ? 'true' : 'false' }}"
        interactive="{{ config('form_components.interactive') ? 'true' : 'false' }}"
    >

    <input
        {{ $attributes->merge(['placeholder' => $title ?: Str::title($name)])->class([
            'form-group__file-holder',
            'fc:peer fc:[grid-area:input] fc:block fc:box-border fc:w-full fc:h-10 fc:m-0 fc:text-[length:inherit] fc:leading-[inherit] fc:font-base fc:text-field-foreground fc:bg-field fc:outline-0',
            'fc:border-current fc:group-[[invalid]]:border-danger' => ! $notched,
            'fc:border-transparent' => $notched,
            'fc:text-ellipsis',
            'fc:px-4' => ! $bottom,
            'fc:p-0' => $bottom,
            'fc:border-2' => ! $bottom,
            'fc:border-0 fc:border-b-2 fc:rounded-none' => $bottom,
            'fc:rounded-none' => ! $bottom && $borderRadius === 'squared',
            'fc:rounded-small' => ! $bottom && $borderRadius === 'rounded-s',
            'fc:rounded-pill' => ! $bottom && $borderRadius === 'rounded',
            'fc:pr-10' => $hasIcon && ! $bottom,
            'fc:pr-6' => $hasIcon && $bottom,
        ]) }}
        type="text"
        value="{{ $value }}"
    >

    @if($notched)
        <fieldset
            aria-hidden="true"
            @class([
                'fc:[grid-area:input] fc:-mt-2 fc:mx-0 fc:mb-0 fc:px-2 fc:py-0 fc:min-w-0 fc:pointer-events-none fc:border-2 fc:border-current fc:font-semibold fc:text-field-foreground fc:group-[[invalid]]:text-danger',
                'fc:*:invisible fc:*:p-0 fc:*:h-4 fc:*:whitespace-nowrap fc:*:overflow-hidden fc:*:transition-[max-width] fc:*:duration-250 fc:*:ease-linear',
                'fc:*:max-w-[0.01px] fc:peer-focus:*:max-w-full fc:peer-not-placeholder-shown:*:max-w-full',
                'fc:rounded-none' => $borderRadius === 'squared',
                'fc:rounded-small' => $borderRadius === 'rounded-s',
                'fc:rounded-pill' => $borderRadius === 'rounded',
            ])
        >
            <legend><span class="fc:px-2">{!! $title !!}</span></legend>
        </fieldset>
    @endif

    <span
        @class([
            'form-group__title fc:font-semibold fc:[&.fc-is-invalid]:text-danger',
            'fc-is-invalid' => $invalidatedTitle && $errors->has($name),
            'fc:[grid-area:title]' => $labelType !== 'floating',
            'fc:mb-1' => $labelType === 'column',
            'fc:min-w-[100px] fc:mr-4 fc:py-2.5' => $labelType === 'row',
            'fc:[grid-area:input] fc:absolute fc:transition-all fc:duration-250 fc:ease-linear' => $labelType === 'floating',
            'fc:top-2.5 fc:opacity-0 fc:peer-focus:-top-2 fc:peer-focus:opacity-100 fc:peer-not-placeholder-shown:-top-2 fc:peer-not-placeholder-shown:opacity-100' => $labelType === 'floating',
            'fc:left-2.5 fc:px-2 fc:rounded-full' => $labelType === 'floating' && ! $bottom,
            'fc:left-0' => $labelType === 'floating' && $bottom,
        ])
    >
        {!! $title !!}
    </span>

    @if($hasIcon)
        <span
            @class([
                'fc:[grid-area:input] fc:flex fc:items-center fc:justify-center fc:box-border fc:h-10 fc:ml-auto fc:select-none fc:pointer-events-none fc:text-inherit fc:fill-current fc:*:grow fc:*:w-auto fc:*:fill-current',
                'fc:min-w-10 fc:*:last-of-type:px-2' => ! $bottom,
                'fc:min-w-6' => $bottom,
            ])
        >
            {!! $icon !!}
        </span>
    @endif

    <x-form::error name="{{ $name }}" />
</label>
