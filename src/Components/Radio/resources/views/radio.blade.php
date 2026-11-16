<label
    @class([
        "fc-form-group fc:group fc:relative fc:grid fc:items-center fc:w-fit fc:grid-cols-[116px_16px] fc:text-left fc:text-sm fc:leading-4 fc:[.fc-form-group+&]:mt-4 fc:font-base fc:dark:scheme-dark",
        "fc:mr-auto fc:[grid-template-areas:'title_input_._error'] fc:*:[[role=alert]]:mt-0 fc:*:[[role=alert]]:ml-2 fc:*:[[role=alert]]:whitespace-nowrap" => $position === 'left',
        "fc:mx-auto fc:[grid-template-areas:'title_input'_'error_error'] fc:*:[[role=alert]]:whitespace-nowrap" => $position !== 'left',
        $label,
    ])
    @error($name) invalid @enderror
    for="{{ $name }}"
>
    <input
        {{ $attributes->class([
            'fc:peer',
            'fc:[grid-area:input] fc:relative fc:inline-block fc:size-4 fc:m-0 fc:cursor-pointer fc:accent-primary' => ! $switch,
            'fc:hidden' => $switch,
        ]) }}
        type="radio"
        id="{{ $id }}"
        name="{{ $name }}"
        {{ $value }}
        interactive="{{ config('form_components.interactive') ? 'true' : 'false' }}"
        @if ($checked) checked @endif
    >

    @if($switch)
        <i class="fc:[grid-area:input] fc:relative fc:inline-block fc:w-[38px] fc:h-[22px] fc:rounded-full fc:bg-muted/20 fc:transition-colors fc:duration-250 fc:peer-checked:bg-primary fc:before:absolute fc:before:left-0 fc:before:w-[34px] fc:before:h-[18px] fc:before:translate-[2px] fc:before:rounded-full fc:before:bg-field fc:before:content-[''] fc:peer-checked:before:translate-x-[18px] fc:peer-checked:before:scale-0 fc:after:absolute fc:after:left-0 fc:after:size-[18px] fc:after:translate-[2px] fc:after:rounded-full fc:after:bg-white fc:peer-checked:after:bg-primary-foreground fc:after:shadow-[0_2px_2px_rgb(0_0_0/0.25)] fc:after:transition-transform fc:after:duration-250 fc:after:content-[''] fc:peer-checked:after:translate-x-[18px]"></i>
    @endif

    <span
        @class([
            'form-group__title fc:[grid-area:title] fc:min-w-[100px] fc:mr-4 fc:font-semibold fc:[&.fc-is-invalid]:text-danger',
            'fc-is-invalid' => $invalidatedTitle && $errors->has($name),
        ])
    >
        {!! $title !!}
    </span>

    <x-form::error name="{{ $name }}" />
</label>
