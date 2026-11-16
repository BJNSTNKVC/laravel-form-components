<!DOCTYPE html>
<html
    lang="en"
    @class(['dark' => request()->boolean('dark')])
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Components Playground</title>
    <style>
        body { margin: 0 auto; max-width: 960px; padding: 2rem 1rem; font-family: system-ui, sans-serif; }
        section { margin-bottom: 3rem; }
        .playground-grid { display: grid; gap: 1.5rem; }
        .dark body { background: #18181b; color: #f4f4f5; }
    </style>
</head>
<body>
    <h1>Form Components Playground</h1>

    @foreach (['column', 'row', 'floating'] as $labelType)
        @foreach (['bottom', 'squared', 'rounded-s', 'rounded'] as $style)
            @php($border = $style === 'bottom' ? 'bottom' : 'full')
            @php($radius = $style === 'bottom' ? 'squared' : $style)

            <section id="{{ $labelType }}-{{ $style }}">
                <h2>{{ ucfirst($labelType) }} Labels, {{ $style === 'bottom' ? 'Bottom Border' : 'Full Border, '.ucfirst($radius) }}</h2>

                <x-form::base action="#" id="form-{{ $labelType }}-{{ $style }}" class="playground-grid">
                    <x-form::input name="input_{{ $labelType }}_{{ $style }}" title="Input" label-type="{{ $labelType }}" border="{{ $border }}" border-radius="{{ $radius }}" />
                    <x-form::email name="email_{{ $labelType }}_{{ $style }}" title="Email" label-type="{{ $labelType }}" border="{{ $border }}" border-radius="{{ $radius }}" />
                    <x-form::password name="password_{{ $labelType }}_{{ $style }}" title="Password" label-type="{{ $labelType }}" border="{{ $border }}" border-radius="{{ $radius }}" />
                    <x-form::search name="search_{{ $labelType }}_{{ $style }}" title="Search" label-type="{{ $labelType }}" border="{{ $border }}" border-radius="{{ $radius }}" />
                    <x-form::textarea name="textarea_{{ $labelType }}_{{ $style }}" title="Textarea" label-type="{{ $labelType }}" border="{{ $border }}" border-radius="{{ $radius }}" />
                    <x-form::select name="select_{{ $labelType }}_{{ $style }}" title="Select" label-type="{{ $labelType }}" border="{{ $border }}" border-radius="{{ $radius }}" values="Option A, Option B" />
                    <x-form::date name="date_{{ $labelType }}_{{ $style }}" title="Date" label-type="{{ $labelType }}" border="{{ $border }}" border-radius="{{ $radius }}" />
                    <x-form::time name="time_{{ $labelType }}_{{ $style }}" title="Time" label-type="{{ $labelType }}" border="{{ $border }}" border-radius="{{ $radius }}" />
                    <x-form::date-time name="date_time_{{ $labelType }}_{{ $style }}" title="Date &amp; Time" label-type="{{ $labelType }}" border="{{ $border }}" border-radius="{{ $radius }}" />
                    <x-form::file name="file_{{ $labelType }}_{{ $style }}" title="File" label-type="{{ $labelType }}" border="{{ $border }}" border-radius="{{ $radius }}" />
                </x-form::base>
            </section>
        @endforeach
    @endforeach

    <section id="errors">
        <h2>Validation Errors</h2>

        <x-form::base action="#" id="form-errors" class="playground-grid">
            <x-form::input name="input_error" title="Input" invalidated-title="true" />
            <x-form::email name="email_error" title="Email" border="full" label-type="row" />
            <x-form::password name="password_error" title="Password" invalidated-title="true" />
            <x-form::search name="search_error" title="Search" invalidated-title="true" />
            <x-form::textarea name="textarea_error" title="Textarea" invalidated-title="true" />
            <x-form::select name="select_error" title="Select" label-type="floating" values="Option A, Option B" placeholder="Choose an option" invalidated-title="true" />
            <x-form::date name="date_error" title="Date" invalidated-title="true" />
            <x-form::time name="time_error" title="Time" invalidated-title="true" />
            <x-form::date-time name="date_time_error" title="Date &amp; Time" invalidated-title="true" />
            <x-form::file name="file_error" title="File" invalidated-title="true" />
            <x-form::checkbox name="checkbox_error" title="Checkbox" switch="false" position="left" invalidated-title="true" />
            <x-form::radio name="radio_error" title="Radio" switch="false" invalidated-title="true" />
        </x-form::base>
    </section>

    <section id="choices">
        <h2>Checkboxes, Radios and Buttons</h2>

        <x-form::base action="#" id="form-choices" class="playground-grid">
            <x-form::checkbox name="checkbox" switch="false" />
            <x-form::checkbox name="checkbox_switch" title="Checkbox Switch" checked="true" />
            <x-form::radio name="radio" switch="false" />
            <x-form::radio name="radio_switch" title="Radio Switch" />
            <x-form::button name="submit" />
            <x-form::button title="Link" link="#" />
        </x-form::base>
    </section>

    <template id="later">
        <x-form::password name="password_later" title="Password Added Later" />
    </template>

    <script type="module" src="/js/app.js"></script>
</body>
</html>
